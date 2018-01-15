<?php

namespace WPS;

require plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';

use WPS\Backend;
use WPS\DB\Settings_Connection;
use WPS\DB\Settings_General;
use WPS\DB\Settings_License;
use WPS\DB\Shop;
use WPS\DB\Products;
use WPS\DB\Variants;
use WPS\DB\Collects;
use WPS\DB\Options;
use WPS\DB\Collections_Custom;
use WPS\DB\Collections_Smart;
use WPS\DB\Images;
use WPS\DB\Tags;
use WPS\DB\Orders;
use WPS\DB\Customers;
use WPS\CPT;
use WPS\Transients;
use WPS\Messages;
use WPS\Webhooks;
use WPS\Utils;
use WPS\License;
use WPS\Progress_Bar;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Class Web Service

*/
class WS {

  protected static $instantiated = null;

  private $Config;
  private $messages;

	/*

	Initialize the class and set its properties.

	*/
	public function __construct($Config) {

		$this->config = $Config;
    $this->connection = $this->config->wps_get_settings_connection();
    $this->connection_option_name = $this->config->settings_connection_option_name;

    $this->general = $this->config->wps_get_settings_general();
    $this->general_option_name = $this->config->settings_general_option_name;
    $this->messages = new Messages();

	}


  /*

	Creates a new class if one hasn't already been created.
	Ensures only one instance is used.

	*/
	public static function instance() {

		if (is_null(self::$instantiated)) {
			self::$instantiated = new self();
		}

		return self::$instantiated;

	}


  /*

  Grab the total call amount from the header response

  */
  public function wps_ws_get_shopify_api_call_amount($response) {

    $headerValue = $response->getHeader('HTTP_X_SHOPIFY_SHOP_API_CALL_LIMIT');

    if (is_array($headerValue) && !empty($headerValue)) {
      return $response->getHeader('HTTP_X_SHOPIFY_SHOP_API_CALL_LIMIT')[0];

    } else {
      return false;
    }

  }


  /*

  Used exclusively for throttling API requests

  */
  public function wps_ws_throttle_requests() {
    sleep(8);
  }


  /*

  Get Error Message
  TODO: Move to Utils
  Returns: (string)

  */
  public function wps_get_error_message($error) {

    if (method_exists($error, 'getResponse') && method_exists($error->getResponse(), 'getBody') && method_exists($error->getResponse()->getBody(), 'getContents')) {

      $responseDecoded = json_decode($error->getResponse()->getBody()->getContents());

      if (is_object($responseDecoded) && isset($responseDecoded->errors)) {

				$errorMessage = $responseDecoded->errors;

      } else {
        $errorMessage = $error->getMessage();
      }


			if (property_exists($errorMessage, "id")) {
				$errorMessage = $errorMessage->id;
			}

			error_log('---- $errorMessage -----');
			error_log(print_r($errorMessage, true));
			error_log('---- /$errorMessage -----');

      return esc_html__('Error: ' . ucfirst($errorMessage), 'wp-shopify');

    } else {

      return esc_html__('Error: ' . ucfirst($error->getMessage()), 'wp-shopify');

    }

  }


  /*

  Callback to the on_headers Guzzle function

  */
  public function wps_ws_check_rate_limit($response) {

		$callTotal = $this->wps_ws_get_shopify_api_call_amount($response);

		error_log(print_r($callTotal, true));

    if ($callTotal === '39/40' || $callTotal === false) {
      $this->wps_ws_throttle_requests();
    }

  }


  /*

  Get Variants
  TODO: Not currently used

  */
  public function wps_ws_get_image_alt($image, $async = false) {

    if (Utils::emptyConnection($this->connection)) {
      wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1046a)');

    } else {

      if (empty($image)) {
        return false;

      } else {

        // TODO: Should we type check or type cast?
        if (is_object($image)) {
          $imageID = $image->id;

        } else if (is_array($image)) {
          $imageID = $image['id'];
        }


        try {

					$url = "/admin/metafields.json";
					$urlParams = "?metafield[owner_id]=" . $imageID . "&metafield[owner_resource]=product_image&limit=250";

					return $this->wps_request(
						'GET',
						$this->get_request_url($url, $urlParams),
						$this->get_request_options(),
						$async
					);

        } catch (RequestException $error) {

          // return new \WP_Error('error', $this->wps_get_error_message($error));
          return esc_html__('Shop Product', 'wp-shopify');

        }

      }

    }

  }


  /*

  Get Products Count

  */
  public function wps_ws_get_products_count() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1045a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1045b)');
		Utils::wps_access_session();

    try {

			error_log('---- Start Products Count -----');
			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/products/count.json"),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (is_object($data) && property_exists($data, 'count')) {

				// $_SESSION['wps_syncing_totals']['products'] = $data->count;
				// session_write_close();
				error_log('---- End Products Count -----');
        wp_send_json_success(['products' => $data->count]);

      } else {
        wp_send_json_error(['products' => 0]);
      }


    } catch (\InvalidArgumentException $error) {

      wp_send_json_error([
				'type' => 'error',
				'message' => $this->wps_get_error_message($error) . ' (code: #1045c)'
			]);

    } catch (RequestException $error) {

      wp_send_json_error([
				'type' => 'error',
				'message' => $this->wps_get_error_message($error) . ' (code: #1045d)'
			]);

    } catch (ClientException $error) {

      wp_send_json_error([
				'type' => 'error',
				'message' => $this->wps_get_error_message($error) . ' (code: #1045e)'
			]);

    // Server errors 5xx
    } catch (ServerException $error) {

      wp_send_json_error([
				'type' => 'error',
				'message' => $this->wps_get_error_message($error) . ' (code: #1045f)'
			]);

    }

  }


  /*

  Get Collections Count
  TODO: Move the "No connection details ..." msg into a constant for reusability

  */
  public function wps_ws_get_collects_count() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1044a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1044b)');
		Utils::wps_access_session();

    try {

			error_log('---- Start Collects Count -----');
			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/collects/count.json"),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (is_object($data) && property_exists($data, 'count')) {
				error_log('---- End Collects Count -----');
        wp_send_json_success(['collects' => $data->count]);

      } else {
        wp_send_json_error(['collects' => 0]);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1044c)');

    }

  }


	/*

	Get Smart Collections Count

	*/
	public function wps_ws_get_smart_collections_count() {

		Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1103a)');
		!Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1103b)');
		Utils::wps_access_session();

		try {

			error_log('---- Start Smart Collections Count -----');
			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/smart_collections/count.json"),
				$this->get_request_options()
			);

			$data = json_decode($response->getBody()->getContents());

			if (is_object($data) && property_exists($data, 'count')) {

				// $_SESSION['wps_syncing_totals']['smart_collections'] = $data->count;
				// session_write_close();

				error_log('---- End Smart Collections Count -----');
				wp_send_json_success(['smart_collections' => $data->count]);

			} else {
				wp_send_json_error(['smart_collections' => 0]);

			}

		} catch (RequestException $error) {

			wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1103c)');

		}

	}


	/*

	Get Custom Collections Count

	*/
	public function wps_ws_get_custom_collections_count() {

		Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1104a)');
		!Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1104b)');
		Utils::wps_access_session();

		try {

			error_log('---- Start Custom Collections Count -----');
			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/custom_collections/count.json"),
				$this->get_request_options()
			);

			$data = json_decode($response->getBody()->getContents());

			if (is_object($data) && property_exists($data, 'count')) {

				// $_SESSION['wps_syncing_totals']['custom_collections'] = $data->count;
				// session_write_close();
				error_log('---- Start End Collections Count -----');
				wp_send_json_success(['custom_collections' => $data->count]);

			} else {
				wp_send_json_error(['custom_collections' => 0]);

			}

		} catch (RequestException $error) {

			wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1104c)');

		}

	}


  /*

  Get Orders Count
  TODO: Combine with other count functions to be more generalized

  */
  public function wps_ws_get_orders_count() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1043a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1043b)');
		Utils::wps_access_session();

		error_log('---- Start Orders Count -----');
    try {

			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/orders/count.json?status=any"),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (is_object($data) && property_exists($data, 'count')) {

				error_log('---- End Orders Count -----');
        wp_send_json_success(['orders' => $data->count]);

      } else {
        wp_send_json_error(['orders' => 0]);

      }

    } catch (RequestException $error) {
      wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1043c)');

    }

  }


  /*

  Get Customers Count
  TODO: Combine with other count functions to be more generalized

  */
  public function wps_ws_get_customers_count() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1042a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1042b)');
		Utils::wps_access_session();

    try {

			error_log('---- Start Customers Count -----');
			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/customers/count.json"),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (is_object($data) && property_exists($data, 'count')) {
				error_log('---- End Customers Count -----');
        wp_send_json_success(['customers' => $data->count]);

      } else {
        wp_send_json_error(['customers' => 0]);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1042c)');

    }

  }


	/*

	Get Webhooks Count

	*/
	public function wps_ws_get_webhooks_count() {

		error_log('---- Start Webhooks Count -----');
		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_nonce_invalid . ' (wps_ws_get_webhooks_count)');
			wp_die();
		}

		if (Utils::emptyConnection($this->connection)) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_connection_not_found . ' (wps_ws_get_webhooks_count)');
			wp_die();
		}

		if (!Utils::isStillSyncing()) {
			wp_send_json_error($this->messages->message_connection_not_syncing . ' (wps_ws_get_webhooks_count)');
			wp_die();
		}

		wp_send_json_success(['webhooks' => 27]);

		// try {
    //
		// 	$response = $this->wps_request(
		// 		'GET',
		// 		$this->get_request_url("/admin/webhooks/count.js"),
		// 		$this->get_request_options()
		// 	);
    //
		// 	error_log('---- URL -----');
		// 	error_log(print_r($this->get_request_url("/admin/webhooks/count.js"), true));
		// 	error_log('---- /URL -----');
    //
		// 	error_log('---- URL OPTIONS -----');
		// 	error_log(print_r($this->get_request_options(), true));
		// 	error_log('---- /URL OPTIONS -----');
    //
		// 	$data = json_decode($response->getBody()->getContents());
    //
		// 	error_log('---- $response -----');
		// 	error_log(print_r($response->getBody()->getContents(), true));
		// 	error_log('---- /$response -----');
    //
		// 	error_log('---- End Webhooks Count -----');
    //
		// 	if (is_object($data) && property_exists($data, 'count')) {
    //
		// 		wp_send_json_success(['webhooks' => $data->count]);
    //
		// 	} else {
		// 		wp_send_json_error(['webhooks' => 0]);
    //
		// 	}
    //
		// } catch (RequestException $error) {
    //
		// 	wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1042c)');
    //
		// }

	}


  /*

  Get Shop Data

  */
  public function wps_ws_get_shop_data() {

    Utils::valid_backend_nonce($_GET['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1041a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1041b)');

    try {

			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/shop.json"),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (is_object($data) && property_exists($data, 'shop')) {
        wp_send_json_success($data);

      } else {
        wp_send_json_error($data);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1041c)');

    }

  }


	/*

	Insert alt text

	*/
	public function wps_insert_alt_text() {

		Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1090a)');

		$Images = new Images();
		$allImages = $Images->get_all_rows();

		error_log('................ Calling API for alt ...');

		// $client = new Guzzle();

    //
    //
    //
		// $promises = (function () use ($image) {
    //
		// 	foreach ($allImages as $image) {
    //

    //
	  //   }
    //
		// })();
    //
		// new Promise\EachPromise($promises, [
	  //   'concurrency' => 4,
	  //   'fulfilled' => function (ResponseInterface $responses) {
		// 			$altText = $Images->get_alt_text_from_response($response);
		// 	 		error_log(print_r($altText, true));
	  //   },
		// ])->promise()->wait();
    //
    //
    //
    //
		// $requests = function() use ($packageNames, &$requestIndexToPackageVendorPairMap) {
		// 	foreach ($packageNames as $packageVendorPair) {
		// 	$requestIndexToPackageVendorPairMap[] = $packageVendorPair;
    //
		// 	yield new GuzzleHttp\Psr7\Request('GET', "https://packagist.org/p/{$packageVendorPair}.json");
		// 	}
		// };


		$Guzzle = new Guzzle();


		// TODO: Should we type check or type cast?


		$requests = function () use ($Guzzle, $allImages) {

			foreach ($allImages as $image) {

				usleep(200000);
				error_log("Starting request...");

				if (is_object($image)) {
					$imageID = $image->id;

				} else if (is_array($image)) {
					$imageID = $image['id'];
				}

				$url = "/admin/metafields.json";
				$urlParams = "?metafield[owner_id]=" . $imageID . "&metafield[owner_resource]=product_image&limit=250";



				yield $Guzzle->requestAsync(
					'GET',
					$this->get_request_url($url, $urlParams),
					$this->get_request_options()
				)->then(function ($response) use ($image, $imageID) {

					error_log("Request completed ...");
					// error_log(print_r($response, true));

				});

			}

		};

		$promise = Promise\each_limit(
	    $requests(),
	    3,
	    function($resp) {
				error_log('---- COMPLETELY DONE -----');
			},
			function($test) {
				error_log('---- REJECTED DONE -----');
				// error_log(print_r($test, true));
				// error_log('---- / REJECTED DONE -----');
			}
		);

		$promise->wait();


    //
    //
    //
    //
    //
    //
    //
    //
    //
    //
		// $requests = function () use($allImages) {
    //
		// 	foreach($allImages as $image) {
    //
		// 		// TODO: Should we type check or type cast?
		//     if (is_object($image)) {
		//       $imageID = $image->id;
    //
		//     } else if (is_array($image)) {
		//       $imageID = $image['id'];
		//     }
    //
		// 		$url = "/admin/metafields.json";
		// 		$urlParams = "?metafield[owner_id]=" . $imageID . "&metafield[owner_resource]=product_image&limit=250";
    //
		// 		yield $this->wps_request(
		// 			'GET',
		// 			$this->get_request_url($url, $urlParams),
		// 			$this->get_request_options(),
		// 			true
		// 		);
    //
		// 	}
    //
		// };
    //
    //
		// (new Pool(
		//     $client,
		//     $requests(),
		//     [
	  //       'concurrency' => 10,
	  //       'fulfilled' => function(Psr\Http\Message\ResponseInterface $response, $index) {
		// 				error_log('---- successful response -----');
	  //       },
	  //       'rejected' => function($reason, $index) {
		// 				error_log('---- rejected response -----');
	  //       }
		//     ]
		// ))->promise()->wait();
    //
    //
    //
		// error_log('---- dddone-----');
    // //
		// // Promise\all($promises)->then(function (array $responses) {
    // //
	  // //   foreach ($responses as $response) {
    // //
		// // 		$altText = $Images->get_alt_text_from_response($response);
		// // 		error_log(print_r($altText, true));
    // //
	  // //   }
    // //
		// // })->wait();
    //
    //






	}


  /*

  Get Products + Variants
  Here we make our requests to the API to insert products and variants

  */
  public function wps_insert_products_data() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_nonce_invalid . ' (wps_insert_products_data)');
			wp_die();
		}

		if (Utils::emptyConnection($this->connection)) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_connection_not_found . ' (wps_insert_products_data)');
			wp_die();
		}

		if (!Utils::isStillSyncing()) {
			wp_send_json_error($this->messages->message_connection_not_syncing . ' (wps_insert_products_data)');
			wp_die();
		}


		$index = 1;
		$insertionResults = [];
		$existingProducts = CPT::wps_get_all_cpt_by_type('wps_products');
		$DB_Variants = new Variants();
		$DB_Products = new Products();
		$DB_Options = new Options();
		$DB_Images = new Images();
		$DB_Tags = new Tags();
		$Progress = new Progress_Bar(new Config());
		$currentPage = Utils::get_current_page($_POST);


    try {

			$response = $DB_Products->get_products_by_page($currentPage);

			$data = json_decode($response->getBody()->getContents());



			/*

			Check for products property on response -- needed to loop

			*/
			if (is_object($data) && property_exists($data, 'products')) {


				/*

				Begin the main loop. May god have mercy on your soul ...

				*/
				foreach ($data->products as $key => $product) {

					// Check if still syncing during each interation to ensure proper sync cancelation
					if (!Utils::isStillSyncing()) {
						wp_send_json_error($this->messages->message_connection_not_syncing . ': ' . $product->title);
						wp_die();
					}


					// If product is published
	        if (property_exists($product, 'published_at') && $product->published_at !== null) {


						/*

						Insert CPT ...

						*/
				    $insertionResults[$product->title]['cpt'] = $customPostTypeID = CPT::wps_insert_or_update_product($product, $existingProducts, $index);

						$product = $DB_Products->modify_product_after_cpt_insert($product, $customPostTypeID);
						$product = $DB_Products->modify_product_before_insert($product, $customPostTypeID);

						/*

						Insert Product ...

						*/
	          $insertionResults[$product->title]['products'] = $DB_Products->insert($product, 'product');


						/*

						Insert tags ...

						*/
						$insertionResults[$product->title]['tags'] = $DB_Tags->insert_tags($product, $customPostTypeID);


						/*

						Insert Variants ...

						*/
						$insertionResults[$product->title]['variants'] = $DB_Variants->insert_variant($product);


						/*

						Insert Options ...

						*/
						$insertionResults[$product->title]['options'] = $DB_Options->insert_option($product);


						/*

						Insert Images ...

						*/
						$insertionResults[$product->title]['images'] = $DB_Images->insert_image($product);


					}


					$index++;
					$Progress->increment_current_amount('products');

				}

				wp_send_json_success($insertionResults);


			} else {
				wp_send_json_error($data->errors);

			}

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1040h)');

    }


  }





  /*

  Get Variants
  TODO: Not currently used

  */
  public function wps_ws_get_variants() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1039a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1039b)');

    $productID = $_POST['productID'];

    if(!isset($_POST['currentPage']) || !$_POST['currentPage']) {
      $currentPage = 1;

    } else {
      $currentPage = $_POST['currentPage'];
    }


    if (empty($productID)) {
      return false;

    } else {

      try {

				$response = $this->wps_request(
					'GET',
					$this->get_request_url("/admin/products/" . $productID . "/variants.json", "?limit=250&page=" . $currentPage),
					$this->get_request_options()
				);

        $data = json_decode($response->getBody()->getContents());

        if (property_exists($data, 'variants')) {
          wp_send_json_success($data);

        } else {
          wp_send_json_error($data->errors);

        }

      } catch (RequestException $error) {

        wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1039c)');

      }

    }

  }


  /*

  Get Collections

  */
  public function wps_insert_custom_collections_data() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_nonce_invalid . ' (wps_insert_custom_collections_data)');
			wp_die();
		}

		if (Utils::emptyConnection($this->connection)) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_connection_not_found . ' (wps_insert_custom_collections_data)');
			wp_die();
		}

		if (!Utils::isStillSyncing()) {
			wp_send_json_error($this->messages->message_connection_not_syncing . ' (wps_insert_custom_collections_data)');
			wp_die();
		}


    try {

      $DB_Images = new Images();
      $DB_Collections_Custom = new Collections_Custom();


			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/custom_collections.json"),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (property_exists($data, "custom_collections")) {

        $results = $DB_Collections_Custom->insert_custom_collections($data->custom_collections);


				if (empty($results)) {
					wp_send_json_error($this->messages->message_insert_custom_collections_error . ' (code: #1038c)');

				} else {
					wp_send_json_success();
				}


      } else {
        wp_send_json_error($data->errors);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1038d)');

    }

	}


  /*

  TODO: NEED TESTING BELOW THIS FUNCTION

  Get Collections

  */
  public function wps_insert_smart_collections_data() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_nonce_invalid . ' (wps_insert_smart_collections_data)');
			wp_die();
		}

		if (Utils::emptyConnection($this->connection)) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_connection_not_found . ' (wps_insert_smart_collections_data)');
			wp_die();
		}

		if (!Utils::isStillSyncing()) {
			wp_send_json_error($this->messages->message_connection_not_syncing . ' (wps_insert_smart_collections_data)');
			wp_die();
		}

		Utils::prevent_timeouts();


    try {

      $DB_Images = new Images();
      $DB_Collections_Smart = new Collections_Smart();

			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/smart_collections.json"),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (property_exists($data, "smart_collections")) {

        $results = $DB_Collections_Smart->insert_smart_collections($data->smart_collections);

				if (empty($results)) {
					wp_send_json_error( $this->messages->message_insert_smart_collections_error . ' (code: #1037d)');

				} else {
					wp_send_json_success();
				}


      } else {
        wp_send_json_error($data->errors);
      }

    } catch (RequestException $error) {
      wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1037d)');

    }

	}


  /*

  Get products from collection

  */
  public function wps_ws_get_products_from_collection() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1036a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1036b)');

    try {

      $collectionID = $_POST['collectionID'];

			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/products.json", "?collection_id=" . $collectionID),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (property_exists($data, 'products')) {
        wp_send_json_success($data->products);

      } else {
        wp_send_json_error($data->errors);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1036c)');

    }

  }


  /*

  Get a list of collects by product ID

  */
  public function wps_insert_collects() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_nonce_invalid . ' (wps_insert_collects)');
			wp_die();
		}

		if (Utils::emptyConnection($this->connection)) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_connection_not_found . ' (wps_insert_collects)');
			wp_die();
		}

		if (!Utils::isStillSyncing()) {
			wp_send_json_error($this->messages->message_connection_not_syncing . ' (wps_insert_collects)');
			wp_die();
		}


    try {

      $DB_Collects = new Collects();

      if(!isset($_POST['currentPage']) || !$_POST['currentPage']) {
        $currentPage = 1;

      } else {
        $currentPage = $_POST['currentPage'];
      }

			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/collects.json", "?limit=250&page=" . $currentPage),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (property_exists($data, "collects")) {

				$resultProducts = $DB_Collects->insert_collects($data->collects);

				if (empty($resultProducts)) {
					wp_send_json_error($this->messages->message_insert_collects_error . ' (code: #1035c)');

				} else {
					wp_send_json_success();
				}

      } else {

        wp_send_json_error($data->errors);

      }


    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1035d)');

    }

  }


  /*

	Get a list of collects by product ID

	*/
	public function wps_ws_get_collects_from_product($productID = null) {

    $ajax = true;

    if ($productID === null) {
      $productID = $_POST['productID'];

    } else {
      $ajax = false;
    }


    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1034a)');

    if ($ajax) {
      Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1034b)');
    }


    try {

			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/collects.json", "?product_id=" . $productID),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (property_exists($data, 'collects')) {

        if ($ajax) {
          wp_send_json_success($data);

        } else {
          return $data;

        }

      } else {
        wp_send_json_error($data->errors);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1034c)');

    }

	}


  /*

  Get a list of collects by collection ID

  */
  public function wps_ws_get_collects_from_collection($collectionID = null) {

    $ajax = true;

    if ($collectionID === null) {
      $collectionID = $_POST['collectionID'];

    } else {
      $ajax = false;
    }

    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1033a)');

    if ($ajax) {
      Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1033b)');
    }


    try {

			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/collects.json", "?collection_id=" . $collectionID),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (property_exists($data, 'collects')) {
        $response = $data->collects;

      } else {
        $response = $data->errors;

      }

    } catch (RequestException $error) {

      $response = $this->wps_get_error_message($error) . ' (code: #1033c)';

    }


    if ($ajax) {

      if (property_exists($response, 'errors')) {
        wp_send_json_success($response);

      } else {
        wp_send_json_error($response);
      }

    } else {
      return $response;

    }


  }


  /*

  Get single collection

  */
  public function wps_ws_get_single_collection() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1032a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1032b)');

    try {

			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/custom_collections/" . $_POST['collectionID'] . ".json"),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (property_exists($data, 'custom_collection')) {
        wp_send_json_success($data);

      } else {
        wp_send_json_error($data->errors);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1032c)');

    }

  }


  /*

  Invalidate the Shopify API connection

  */
  public function wps_ws_end_api_connection() {

    if (Utils::emptyConnection($this->connection)) {
      return new \WP_Error('error', $this->messages->message_connection_disconnect_invalid_access_token . ' (code: #1031a)');

    } else {

      try {

				$headers = array(
          "Content-Type" => "application/json",
          "Accept" => "application/json",
          "Content-Length" => "0"
        );

				// TODO: Check result of deletion
				$response = $this->wps_request(
					'DELETE',
					$this->get_request_url("/admin/api_permissions/current.json"),
					$this->get_request_options($headers)
				);

        return true;

      } catch (RequestException $error) {

        return new \WP_Error('error', $this->wps_get_error_message($error) . ' (code: #1031b)');

      }

    }

  }


	/*

	Attaches all webhooks

	*/
	public function wps_ws_register_all_webhooks() {

		$Webhooks = new Webhooks($this->config);

		if (isset($_POST['removalErrors']) && $_POST['removalErrors']) {
			$webhooksErrorList = $_POST['removalErrors'];

		} else {
			$webhooksErrorList = [];
		}

		$webhooksDefaultList = $Webhooks->default_topics();

		$webhooksToRegister = array_diff_key($webhooksDefaultList, $webhooksErrorList);

		$registerResults = $Webhooks->wps_webhooks_register($webhooksToRegister);

		if (is_array($registerResults)) {

			// Contains an array of topics and webhook IDs on success or false on error
			$finalWebhooksResult = array_merge($webhooksErrorList, $registerResults);

			$registerErrors = $Webhooks->filter_for_register_errors($finalWebhooksResult);

			if (empty($registerErrors)) {
				wp_send_json_success([
					'warnings' => false
				]);

			} else {
				wp_send_json_success([
					'warnings' => $registerErrors
				]);

			}

		} else {
			wp_send_json_error();
		}

	}


  /*

	Get Webhooks

	*/
	public function wps_ws_get_webhooks() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1030a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1030b)');

    try {
			error_log('---- start wps_ws_get_webhooks -----');

			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/webhooks.json"),
				$this->get_request_options()
			);

      $data = $response->getBody()->getContents();
			error_log('---- end wps_ws_get_webhooks -----');
      wp_send_json_success($data);


    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1030c)');

    }

	}


  /*

  Delete Webhooks
  TODO: Are we actually deleting? Do we actually need?

  */
  public function wps_ws_delete_webhook() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1029a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (code: #1029b)');


    if (property_exists($this->connection, 'webhook_id') && $this->connection->webhook_id) {

      try {

				$response = $this->wps_request(
					'GET',
					$this->get_request_url("/admin/webhooks/" . $this->connection->webhook_id . ".json"),
					$this->get_request_options()
				);

        $data = $response->getBody()->getContents();
        wp_send_json_success($data);

      } catch (RequestException $error) {

        wp_send_json_error( $this->wps_get_error_message($error) . ' (code: #1029c)');

      }

    } else {

      return new \WP_Error('error', $this->messages->message_webhooks_no_id_set . ' (code: #1029d)');

    }

  }


  /*

  Get Progress Count

  */
  function wps_get_progress_count() {
    wp_send_json_success($_SESSION);
  }


  /*

  Update Settings General

  */
  public function wps_update_settings_general() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #103a)');

    global $wp_rewrite;

    $DB_Settings_General = new Settings_General();

    $newGeneralSettings = array();

    if (isset($_POST['wps_settings_general_products_url']) && $_POST['wps_settings_general_products_url']) {
      $newGeneralSettings['url_products'] = $_POST['wps_settings_general_products_url'];
    }

    if (isset($_POST['wps_settings_general_collections_url']) && $_POST['wps_settings_general_collections_url']) {
      $newGeneralSettings['url_collections'] = $_POST['wps_settings_general_collections_url'];
    }

    if (isset($_POST['wps_settings_general_url_webhooks']) && $_POST['wps_settings_general_url_webhooks']) {
      $newGeneralSettings['url_webhooks'] = $_POST['wps_settings_general_url_webhooks'];
    }

    if (isset($_POST['wps_settings_general_num_posts'])) {

      if ($_POST['wps_settings_general_num_posts']) {
        $newGeneralSettings['num_posts'] = $_POST['wps_settings_general_num_posts'];

      } else {
        $newGeneralSettings['num_posts'] = null;

      }

    }

		if (isset($_POST['wps_settings_general_title_as_alt'])) {
			$newGeneralSettings['title_as_alt'] = (int)$_POST['wps_settings_general_title_as_alt'];
		}

    if (isset($_POST['wps_settings_general_styles_all'])) {
      $newGeneralSettings['styles_all'] = (int)$_POST['wps_settings_general_styles_all'];
    }

    if (isset($_POST['wps_settings_general_styles_core'])) {
      $newGeneralSettings['styles_core'] = (int)$_POST['wps_settings_general_styles_core'];
    }

    if (isset($_POST['wps_settings_general_styles_grid'])) {
      $newGeneralSettings['styles_grid'] = (int)$_POST['wps_settings_general_styles_grid'];
    }

    if (isset($_POST['wps_settings_general_price_with_currency'])) {
      $newGeneralSettings['price_with_currency'] = (int)$_POST['wps_settings_general_price_with_currency'];
    }

    if (isset($_POST['wps_settings_general_cart_loaded'])) {
      $newGeneralSettings['cart_loaded'] = (int)$_POST['wps_settings_general_cart_loaded'];
    }

    $results = $DB_Settings_General->update_general($newGeneralSettings);

    Transients::delete_cached_settings();
    set_transient('wps_settings_updated', $newGeneralSettings);

    wp_send_json_success($results);

  }


  /*

  Reset rewrite rules on CTP url change

  */
  public function wps_reset_rewrite_rules($old_value, $new_value) {
    update_option('rewrite_rules', '');
  }


  /*

  Reset rewrite rules on CTP url change

  */
  public function wps_get_connection() {

    Utils::valid_backend_nonce($_GET['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #104a)');

    if (get_transient('wps_settings_connection')) {
      $connectionData = get_transient('wps_settings_connection');

    } else {

      $DB_Settings_Connection = new Settings_Connection();
      $connectionData = $DB_Settings_Connection->get();
      set_transient('wps_settings_connection', $connectionData);

    }

    wp_send_json_success($connectionData);

  }


	/*

	Reset rewrite rules on CTP url change

	*/
	public function wps_remove_connection() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_nonce_invalid . ' (wps_remove_connection)');
			wp_die();
		}

		$response_connection_settings = $this->wps_delete_settings_connection();

		if (is_wp_error($response_connection_settings)) {
			wp_send_json_error($response_connection_settings->get_error_message()  . ' (wps_remove_connection)');

		} else {
			wp_send_json_success();
		}

	}


  /*

  Insert connection data

  */
  public function wps_insert_connection() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_nonce_invalid . ' (wps_insert_connection)');
			wp_die();
		}

		if (!Utils::isStillSyncing()) {
			wp_send_json_error($this->messages->message_connection_not_syncing . ' (wps_insert_connection)');
			wp_die();
		}

    $DB_Settings_Connection = new Settings_Connection();
    $connectionData = $_POST['connectionData'];
    $connectionData = (array) $connectionData;

    $results = $DB_Settings_Connection->insert_connection($connectionData);

    if ($results === false) {
			error_log('Error inserting connection data');
      wp_send_json_error($this->messages->message_connection_save_error . ' (wps_insert_connection)');

    } else {
      wp_send_json_success();

    }

  }


  /*

  Insert Shop Data

  */
  public function wps_insert_shop() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_nonce_invalid . ' (wps_insert_shop)');
			wp_die();
		}

		if (!Utils::isStillSyncing()) {
			wp_send_json_error($this->messages->message_connection_not_syncing . ' (wps_insert_shop)');
			wp_die();
		}


		$DB_Shop = new Shop();
    $shopData = $_POST['shopData'];
    $results = $DB_Shop->insert_shop($shopData);

		if (empty($results)) {
			wp_send_json_error($this->message_connection_save_error . ' (code: #106b)');

		} else {
			wp_send_json_success();
		}


  }


  /*

  Delete Shop Data

  */
  public function wps_delete_shop() {

    $DB_Shop = new Shop();

    if (!$DB_Shop->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_shop_error . ' (code: #107a)');

    } else {
      return true;
    }

  }


  /*

	Delete the config data
  TODO: Support multiple connections by making connection ID dynamic?

	*/
	public function wps_delete_settings_connection() {

		$DB_Settings_Connection = new Settings_Connection();

    $deeltion = $DB_Settings_Connection->delete();

    if (!$deeltion) {
      return new \WP_Error('error', $this->messages->message_delete_connection_error . ' (code: #108a)');

    } else {
      return true;
    }

	}


  /*

  Delete the synced Shopify data

  */
  public function wps_delete_synced_data() {

    $result = true;
    $Backend = new Backend($this->config);

    if (!$Backend->wps_delete_posts('wps_products')) {
      $result = new \WP_Error('error', $this->messages->message_delete_cpt_products_error . ' (code: #109a)');
    }

    if (!$Backend->wps_delete_posts('wps_collections')) {
      $result = new \WP_Error('error', $this->messages->message_delete_cpt_collections_error . ' (code: #109b)');
    }

    return $result;

  }


  /*

  wps_delete_images

  */
  public function wps_delete_images() {

    $Images = new Images();

    if (!$Images->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_product_images_error . ' (code: #1010a)');

    } else {
      return true;
    }

  }


  /*

  wps_delete_inventory

  */
  public function wps_delete_inventory() {

    $Inventory = new Inventory();

    if (!$Inventory->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_product_inventory_error . ' (code: #1011a)');

    } else {
      return true;
    }

  }


  /*

  wps_delete_collects

  */
  public function wps_delete_collects() {

    $Collects = new Collects();

    if (!$Collects->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_collects_error . ' (code: #1012a)');

    } else {
      return true;
    }

  }


  /*

  wps_delete_options

  */
  public function wps_delete_tags() {

    $Tags = new Tags();

    if (!$Tags->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_product_tags_error . ' (code: #1013a)');

    } else {
      return true;
    }

  }


  /*

  wps_delete_options

  */
  public function wps_delete_options() {

    $Options = new Options();

    if (!$Options->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_product_options_error . ' (code: #1014a)');

    } else {
      return true;
    }

  }


  /*

  wps_delete_variants

  */
  public function wps_delete_variants() {

    $Variants = new Variants();

    if (!$Variants->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_product_variants_error . ' (code: #1015a)');

    } else {
      return true;
    }

  }


  /*

  wps_delete_products

  */
  public function wps_delete_products() {

    $Products = new Products();

    if (!$Products->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_products_error . ' (code: #1016a)');

    } else {
      return true;
    }

  }


  /*

  wps_delete_products

  */
  public function wps_delete_custom_collections() {

    $Collections_Custom = new Collections_Custom();

    if (!$Collections_Custom->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_custom_collections_error . ' (code: #1017a)');

    } else {
      return true;
    }

  }


  /*

  wps_delete_products

  */
  public function wps_delete_smart_collections() {

    $Collections_Smart = new Collections_Smart();

    if (!$Collections_Smart->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_smart_collections_error . ' (code: #1017a)');

    } else {
      return true;
    }

  }


  /*

  wps_delete_orders

  */
  public function wps_delete_orders() {

    $Orders = new Orders();

    if (!$Orders->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_orders_error . ' (code: #1018a)');

    } else {
      return true;
    }

  }


  /*

  wps_delete_customers

  */
  public function wps_delete_customers() {

    $Customers = new Customers();

    if (!$Customers->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_customers_error . ' (code: #1019a)');

    } else {
      return true;
    }

  }


  /*

  Drop databases used during uninstall

  */
  public function wps_drop_databases() {

    $DB_Shop = new Shop();
    $DB_Settings_General = new Settings_General();
    $DB_Settings_License = new Settings_License();
    $DB_Settings_Connection = new Settings_Connection();
    $Collections_Smart = new Collections_Smart();
    $Collections_Custom = new Collections_Custom();
    $Products = new Products();
    $Variants = new Variants();
    $Options = new Options();
    $Tags = new Tags();
    $Collects = new Collects();
    $Images = new Images();
    $Transients = new Transients();
    $Orders = new Orders();
    $Customers = new Customers();

    $results['shop'] = $DB_Shop->delete_table();
    $results['settings_general'] = $DB_Settings_General->delete_table();
    $results['settings_license'] = $DB_Settings_License->delete_table();
    $results['settings_connection'] = $DB_Settings_Connection->delete_table();
    $results['collections_smart'] = $Collections_Smart->delete_table();
    $results['collections_custom'] = $Collections_Custom->delete_table();
    $results['products'] = $Products->delete_table();
    $results['variants'] = $Variants->delete_table();
    $results['options'] = $Options->delete_table();
    $results['tags'] = $Tags->delete_table();
    $results['collects'] = $Collects->delete_table();
    $results['images'] = $Images->delete_table();
    $results['orders'] = $Orders->delete_table();
    $results['customers'] = $Customers->delete_table();
    $results['transients'] = $Transients->delete_all_cache();

    return $results;

  }


  /*

  Uninstall consumer
  Returns: Response object

  Need to do a few things here ...

  1. Remove webhooks
  3. Remove the wps config values from the database
  4. Delete cache


  TODO: Since invalidating the main Shopify API connection is
  performed asynchronously, we should break that into its own
  request; perhaps after this one.

  Each deletion returns either type boolean of TRUE or a type
  STRING containing the error message.

  */
  public function wps_uninstall_consumer($ajax = true, $async = false) {

    if ($_POST['action'] === 'wps_uninstall_consumer') {
      $ajax = true;
			$async = true;

    } else {
      $ajax = false;
    }

    if ($ajax) {
      Utils::valid_uninstall_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (code: #1020a)');
    }

    $results = array();
		$License = new License($this->config);
    $Transients = new Transients();
		$Webhooks = new Webhooks($this->config);
    $DB_Settings_Connection = new Settings_Connection();
    $connection = $DB_Settings_Connection->get_column_single('api_key');


		/*

		Step 1. Remove all product data including CPTs

		*/
		$results = $this->wps_uninstall_all_data();


    if (!empty($connection)) {

			/*

			Step 2. Remove & Deactivate any license keys

			*/
			$response_license = $License->wps_deactivate_plugin_license();

			if (is_wp_error($response_license)) {
				$results['connection_settings'] = $response_license->get_error_message()  . ' (code: #1020d)';

			} else {
				$results['connection_settings'] = $response_license;
			}


			/*

			Step 3. Remove Webhooks

			*/
			$response_webhooks = $Webhooks->remove_webhooks(false, $async);


			if (is_wp_error($response_webhooks)) {
				$results['connection_api'] = $response_webhooks->get_error_message()  . ' (code: #1020b)';

			} else {
				$results['connection_api'] = 1;
			}


    }


    if ($ajax) {
      wp_send_json_success($results);

    } else {
      return $results;

    }


  }


	/*

	Remove all data
	Returns: results object

	*/
	public function wps_uninstall_all_data() {

		Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid  . ' (code: #1068a)');

		$results = $this->wps_uninstall_product_data(false);

		/*

		Remove CPTs

		*/
		$response_cpt = $this->wps_delete_synced_data();

		if (is_wp_error($response_cpt)) {
		  $results['cpt'] = $response_cpt->get_error_message();

		} else {
		  $results['cpt'] = $response_cpt;
		}

		return $results;

	}


  /*

  Uninstall product-related data
  Returns: Response object

  */
  public function wps_uninstall_product_data($ajax = true) {

    if ($_POST['action'] === 'wps_uninstall_product_data') {
      $ajax = true;

    } else {
      $ajax = false;
    }


    if ($ajax) {
      Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid  . ' (code: #1021a)');
    }


    $results = array();
    $Transients = new Transients();
		$Webhooks = new Webhooks($this->config);
    $DB_Settings_Connection = new Settings_Connection();
    $connection = $DB_Settings_Connection->get_column_single('domain');


    /*

    Remove Shop

    */
    $response_shop = $this->wps_delete_shop();

    if (is_wp_error($response_shop)) {
      $results['shop'] = $response_shop->get_error_message()  . ' (code: #1021b)';

    } else {
      $results['shop'] = $response_shop;
    }


		/*

		Remove Webhooks

		*/
		$response_webhooks = $Webhooks->remove_webhooks(false, true);


		if (is_wp_error($response_webhooks)) {
			$results['connection_api'] = $response_webhooks->get_error_message()  . ' (code: #1021bb)';

		} else {
			$results['connection_api'] = 1;
		}


    /*

    Remove Products

    */
    $response_products = $this->wps_delete_products();

    if (is_wp_error($response_products)) {
      $results['products'] = $response_products->get_error_message()  . ' (code: #1021c)';

    } else {
      $results['products'] = $response_products;
    }


    /*

    Remove Custom Collections

    */
    $response_collections_custom = $this->wps_delete_custom_collections();

    if (is_wp_error($response_collections_custom)) {
      $results['custom_collections'] = $response_collections_custom->get_error_message()  . ' (code: #1021d)';

    } else {
      $results['custom_collections'] = $response_collections_custom;
    }


    /*

    Remove Smart Collections

    */
    $response_collections_smart = $this->wps_delete_smart_collections();

    if (is_wp_error($response_collections_smart)) {
      $results['smart_collections'] = $response_collections_smart->get_error_message()  . ' (code: #1021e)';

    } else {
      $results['smart_collections'] = $response_collections_smart;
    }


    /*

    Remove Collects

    */
    $response_collects = $this->wps_delete_collects();

    if (is_wp_error($response_collects)) {
      $results['collects'] = $response_collects->get_error_message()  . ' (code: #1021f)';

    } else {
      $results['collects'] = $response_collects;
    }


    /*

    Remove Variants

    */
    $response_variants = $this->wps_delete_variants();

    if (is_wp_error($response_variants)) {
      $results['variants'] = $response_variants->get_error_message()  . ' (code: #1021g)';

    } else {
      $results['variants'] = $response_variants;
    }


    /*

    Remove Options

    */
    $response_options = $this->wps_delete_options();

    if (is_wp_error($response_options)) {
      $results['options'] = $response_options->get_error_message()  . ' (code: #1021h)';

    } else {
      $results['options'] = $response_options;
    }


    /*

    Remove Tags

    */
    $response_tags = $this->wps_delete_tags();

    if (is_wp_error($response_tags)) {
      $results['tags'] = $response_tags->get_error_message()  . ' (code: #1021i)';

    } else {
      $results['tags'] = $response_tags;
    }


    /*

    Remove Images

    */
    $response_images = $this->wps_delete_images();

    if (is_wp_error($response_images)) {
      $results['images'] = $response_images->get_error_message()  . ' (code: #1021j)';

    } else {
      $results['images'] = $response_images;
    }


    /*

    Remove Orders

    */
    $response_orders = $this->wps_delete_orders();

    if (is_wp_error($response_orders)) {
      $results['orders'] = $response_orders->get_error_message()  . ' (code: #1021k)';

    } else {
      $results['orders'] = $response_orders;
    }


    /*

    Remove Customers

    */
    $response_customers = $this->wps_delete_customers();

    if (is_wp_error($response_customers)) {
      $results['customers'] = $response_customers->get_error_message()  . ' (code: #1021l)';

    } else {
      $results['customers'] = $response_customers;
    }


    /*

    Remove Transients

    */
    $response_transients = $Transients->delete_all_cache();

    if (is_wp_error($response_transients)) {
      $results['transients'] = $response_transients->get_error_message()  . ' (code: #1021m)';

    } else {
      $results['transients'] = $response_transients;
    }


    if ($ajax) {

      wp_send_json_success($results);

    } else {

      return $results;

    }


  }


	/*

	Turns the syncing flag off

	*/
	public function turn_off_syncing() {
		$this->wps_ws_set_syncing_indicator(false, 0);
	}


  /*

  Set syncing indicator

  */
  public function wps_ws_set_syncing_indicator($ajax = true, $flag = false) {

    if (isset($_POST['action']) && $_POST['action'] === 'wps_ws_set_syncing_indicator') {
      $ajax = true;
			$flag = $_POST['syncing'];

    } else {
      $ajax = false;
    }

		Utils::wps_access_session();

		$results = [];
		$_SESSION['wps_is_syncing'] = $flag;
		$results['syncing_indicator'] = $flag;

		if ($ajax) {
      wp_send_json_success($results);

    } else {
      return $results;

    }


  }


  /*

  Clear Cache

	Once this point is reached, all the data has been synced.
	set_transident allows for /products and /collections permalinks to work

	TODO: Make this more clear from within JS side
	TODO: Modularize

  */
  public function wps_clear_cache() {

    Utils::valid_cache_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid  . ' (code: #1023a)');

    $Transients = new Transients();

		set_transient('wps_settings_updated', true);
		set_transient('wps_recently_connected', true);

    $results = $Transients->delete_all_cache();

    if (is_wp_error($results)) {
      wp_send_json_error(esc_html__($results->get_error_message()  . ' (code: #1023b)', 'wp-shopify'));

    } else {

			flush_rewrite_rules();
      wp_send_json_success($results);

    }

  }


  /*

  Sync with CPT

  CURRENTLY NOT USED

  */
  public function wps_sync_with_cpt() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid  . ' (code: #1024a)');

    $Utils = new Utils();
    $DB_Products = new Products();
    $products = $DB_Products->get_products();
    $results = array();


    foreach($products as $product) {
      $results[] = $DB_Products->update_post_content_if_changed($product);
    }

    $filteredResults = array_filter($results, array($Utils, 'filter_errors'));

    if (!empty($filteredResults)) {

      $filteredResults = array_map(
        array($Utils, 'filter_errors_with_messages'),
        array_keys($filteredResults),
        $filteredResults
      );

      wp_send_json_error($filteredResults);

    } else {
      wp_send_json_success();

    }

  }


  /*

  Insert Orders

  */
  public function wps_insert_orders() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_nonce_invalid . ' (wps_insert_orders)');
			wp_die();
		}

		if (Utils::emptyConnection($this->connection)) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_connection_not_found . ' (wps_insert_orders)');
			wp_die();
		}

		if (!Utils::isStillSyncing()) {
			wp_send_json_error($this->messages->message_connection_not_syncing . ' (wps_insert_orders)');
			wp_die();
		}


    try {

      $DB_Orders = new Orders();

      if (!isset($_POST['currentPage']) || !$_POST['currentPage']) {
        $currentPage = 1;

      } else {
        $currentPage = $_POST['currentPage'];
      }

			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/orders.json", "?status=any&page=" . $currentPage),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (is_object($data) && property_exists($data, 'orders')) {

        /*

        This is where the bulk of product data is inserted into the database. The
        "insert_products" method inserts both the CPT's and custom WPS table data.

        */
        $resultOrders = $DB_Orders->insert_orders( $data->orders );

				error_log('---- $resultOrders -----');
				error_log(print_r($resultOrders, true));
				error_log('---- /$resultOrders -----');

        if (empty($resultOrders)) {
          wp_send_json_error($this->messages->message_syncing_orders_error  . ' (code: #1025c)');
        }

        wp_send_json_success();

      } else {
        wp_send_json_error($data->errors);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error)  . ' (code: #1025d)');

    }

  }


  /*

  Insert Customers

  */
  public function wps_insert_customers() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_nonce_invalid . ' (wps_insert_customers)');
			wp_die();
		}

		if (Utils::emptyConnection($this->connection)) {
			error_log('---- empty connection at wps_insert_customers -----');
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_connection_not_found . ' (wps_insert_customers)');
			wp_die();
		}

		if (!Utils::isStillSyncing()) {
			wp_send_json_error($this->messages->message_connection_not_syncing . ' (wps_insert_customers)');
			wp_die();
		}


    try {

      $Utils = new Utils();
      $DB_Customers = new Customers();

      if (!isset($_POST['currentPage']) || !$_POST['currentPage']) {
        $currentPage = 1;

      } else {
        $currentPage = $_POST['currentPage'];
      }

			$response = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/customers.json", "?page=" . $currentPage),
				$this->get_request_options()
			);

      $data = json_decode($response->getBody()->getContents());

      if (is_object($data) && property_exists($data, 'customers')) {

        /*

        This is where the bulk of product data is inserted into the database. The
        "insert_products" method inserts both the CPT's and custom WPS table data.

        */
        $results = $DB_Customers->insert_customers( $data->customers );


        if (empty($results)) {
          wp_send_json_error($this->messages->message_syncing_customers_error  . ' (code: #1026c)');
        }

        wp_send_json_success();

      } else {
        wp_send_json_error($data->errors);

      }


    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error)  . ' (code: #1026d)');

    }

  }


	/*

	Returns hmac value
	Used: to verify webhooks

	*/
	public static function get_header_hmac() {
		return $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
	}


	/*

	Returns request headers + options for Guzzle

	Param: $newHeaders array
	Param: $newOptions array

	get_request_options(['Content-Type' => 'text/json'], ['query' => $stuff])

	*/
	public function get_request_options($newHeaders = false, $newOptions = false, $shopify = true) {

		$finalOptions = [];
		$defaultHeaders = [];

		if ($shopify) {

			$finalOptions = [
				'headers' => [
					'Authorization' => 'Basic ' . base64_encode($this->connection->api_key . ':' . $this->connection->password)
				],
				'on_headers' => function(ResponseInterface $response) {
					$this->wps_ws_check_rate_limit($response);
				}
			];

		}

		if (is_array($newHeaders) && !empty($newHeaders)) {

			if (isset($finalOptions['headers']) && $finalOptions['headers']) {

				$defaultHeaders = $finalOptions['headers'];
				$finalOptions['headers'] = array_merge($defaultHeaders, $newHeaders);

			}

		}

		if (is_array($newOptions) && !empty($newOptions)) {
			$finalOptions = array_merge($finalOptions, $newOptions);
		}

		return $finalOptions;

	}


	/*

	Returns request url

	*/
	public function get_request_url($endpoint, $params = false) {

		return "https://" . $this->connection->domain . $endpoint . $params;

	}


	/*

	Request

	*/
	public function wps_request($method, $url, $options = false, $async = false) {

		usleep(180000);

		$Guzzle = new Guzzle();


		if (empty($options)) {
			$options = [];
		}


		if ($async) {

			return $Guzzle->requestAsync(
				$method,
				$url,
				$options
			);

		} else {

			return $guzzelResponse = $Guzzle->request(
				$method,
				$url,
				$options
			);

		}


	}


	/*

	wps_ws_testing_private_app

	*/
	public function wps_ws_testing_private_app() {

		try {

      // $url = "https://" . $this->connection->domain . "/admin/customers.json?page=1";

      /*

      If Access Token is expired or wrong the follow error will result:
      "[API] Invalid API key or access token (unrecognized login or wrong password)"

      */
      // $headers = array(
      //   'Authorization' => 'Basic ' . base64_encode($this->connection->api_key . ':' . $this->connection->password)
      // );

			$guzzelResponse = $this->wps_request(
				'GET',
				$this->get_request_url("/admin/customers.json", "?page=1"),
				$this->get_request_options()
			);

      $data = json_decode($guzzelResponse->getBody()->getContents());

      if (is_object($data) && property_exists($data, 'customers')) {
				wp_send_json_success($data);

      } else {

        wp_send_json_error($data->errors);

      }


    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error)  . ' dead');

    }

	}







	/*

	Save Counts

	*/
	public function save_counts() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_nonce_invalid . ' (wps_insert_customers)');
			wp_die();
		}

		if (Utils::emptyConnection($this->connection)) {
			error_log('---- empty connection at save_counts -----');
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_connection_not_found . ' (wps_insert_customers)');
			wp_die();
		}

		// if (!Utils::isStillSyncing()) {
		// wp_send_json_error($this->messages->message_connection_not_syncing . ' (wps_insert_customers)');
		// 	wp_die();
		// }


		// error_log('---- $_POST -----');
		// error_log(print_r($_POST['counts'], true));
		// error_log('---- /$_POST -----');


		if (isset($_POST['counts']) && $_POST['counts']) {
			$counts = Utils::shift_arrays_up($_POST['counts']);

		} else {
			$counts = [];
		}


		if (!empty($counts)) {

			Utils::wps_access_session();

			if (isset($_SESSION['wps_syncing_totals']['smart_collections'])) {
				$_SESSION['wps_syncing_totals']['smart_collections'] = $counts['smart_collections'];
			}

			if (isset($_SESSION['wps_syncing_totals']['custom_collections'])) {
				$_SESSION['wps_syncing_totals']['custom_collections'] = $counts['custom_collections'];
			}

			if (isset($_SESSION['wps_syncing_totals']['products'])) {
				$_SESSION['wps_syncing_totals']['products'] = $counts['products'];
			}

			if (isset($_SESSION['wps_syncing_totals']['collects'])) {
				$_SESSION['wps_syncing_totals']['collects'] = $counts['collects'];
			}

			if (isset($_SESSION['wps_syncing_totals']['orders'])) {
				$_SESSION['wps_syncing_totals']['orders'] = $counts['orders'];
			}

			if (isset($_SESSION['wps_syncing_totals']['customers'])) {
				$_SESSION['wps_syncing_totals']['customers'] = $counts['customers'];
			}

			$counts['webhooks'] = 27;

			Utils::wps_close_session_write();

			wp_send_json_success($counts);

		}

	}


	/*

	Get Total Counts

	*/
	public function get_total_counts() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->turn_off_syncing();
			wp_send_json_error($this->messages->message_nonce_invalid . ' (wps_insert_customers)');
			wp_die();
		}

		Utils::wps_access_session();
		wp_send_json_success($_SESSION['wps_syncing_totals']);

	}

}
