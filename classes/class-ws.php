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
use WPS\CPT;
use WPS\Transients;
use WPS\Messages;
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

Class WS

*/
if (!class_exists('WS')) {

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

		Always returns a JSON object to the client in this format:

		{
			success: false,
			data: {
				type: 'error',
				message: <message>
			}
		}

		*/
		public function send_error($message = '') {

			wp_send_json_error([
				'type' => 'error',
				'message' => $message
			]);

			wp_die();

		}


		/*

		Always returns a JSON object to the client in this format:

		{
			success: true,
			data: {
				type: 'warning',
				message: <message>
			}
		}

		*/
		public function send_warning($message = '') {

			wp_send_json_success([
				'type' => 'warning',
				'message' => $message
			]);

			wp_die();

		}


		/*

		Always returns a JSON object to the client in this format:

		{
			success: true,
			data: $data
		}

		*/
		public function send_success($data = false) {

			wp_send_json_success($data);
			wp_die();

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











	public function wps_get_status_code($response) {

		if (method_exists($response, 'getResponse') && method_exists($response->getResponse(), 'getStatusCode')) {
			return $response->getResponse()->getStatusCode();

		} else {
			return '5xx'; // Generic error response
		}

	}







	public function wps_get_error_message($response) {

		$responseCode = $this->wps_get_status_code($response);

		switch ($responseCode) {

			case 400:
				return $this->messages->message_shopify_api_400;
				break;

			case 401:
				return $this->messages->message_shopify_api_401;
				break;

			case 402:
				return $this->messages->message_shopify_api_402;
				break;

			case 403:
				return $this->messages->message_shopify_api_403;
				break;

			case 404:
				return $this->messages->message_shopify_api_404;
				break;

			case 406:
				return $this->messages->message_shopify_api_406;
				break;

			case 422:
				return $this->messages->message_shopify_api_422;
				break;

			case 429:
				return $this->messages->message_shopify_api_429;
				break;

			case 500:
				return $this->messages->message_shopify_api_500;
				break;

			case 501:
				return $this->messages->message_shopify_api_501;
				break;

			case 503:
				return $this->messages->message_shopify_api_503;
				break;

			case 504:
				return $this->messages->message_shopify_api_504;
				break;

			default:
				return $this->messages->message_shopify_api_generic;
				break;

		}

	}



	  // /*
		//
	  // Get Error Message
	  // TODO: Move to Utils
	  // Returns: (string)
		//
	  // */
	  // public function wps_get_error_message($error) {
		//
		// 	$errorCode = $this->wps_get_status_code($error);
		//
		//
		//
	  //   // if (method_exists($error, 'getResponse') && method_exists($error->getResponse(), 'getBody') && method_exists($error->getResponse()->getBody(), 'getContents')) {
		// 	//
	  //   //   $responseDecoded = json_decode($error->getResponse()->getBody()->getContents());
		// 	//
	  //   //   if (is_object($responseDecoded) && isset($responseDecoded->errors)) {
		// 	// 		$errorMessage = $responseDecoded->errors;
		// 	//
	  //   //   } else {
	  //   //     $errorMessage = $error->getMessage();
	  //   //   }
		// 	//
		// 	// 	if (property_exists($errorMessage, "id")) {
		// 	// 		$errorMessage = $errorMessage->id;
		// 	// 	}
		// 	//
	  //   //   return esc_html__('Error: ' . ucfirst($errorMessage), 'wp-shopify');
		// 	//
	  //   // } else {
		// 	//
	  //   //   return esc_html__('Error: ' . ucfirst($error->getMessage()), 'wp-shopify');
		// 	//
	  //   // }
		//
	  // }


	  /*

	  Callback to the on_headers Guzzle function

	  */
	  public function wps_ws_check_rate_limit($response) {

			$callTotal = $this->wps_ws_get_shopify_api_call_amount($response);

	    if ($callTotal === '39/40' || $callTotal === false) {
	      $this->wps_ws_throttle_requests();
	    }

	  }


	  /*

	  Get alt text

	  */
	  public function wps_ws_get_image_alt($image, $async = false) {

	    if (Utils::emptyConnection($this->connection)) {
				return esc_html__('Shop Product', 'wp-shopify');
			}

			if (empty($image)) {
				return esc_html__('Shop Product', 'wp-shopify');

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
						false
					);

				} catch (RequestException $error) {
					return esc_html__('Shop Product', 'wp-shopify');

				}

			}

	  }


	  /*

	  Get Products Count

	  */
	  public function wps_ws_get_products_count() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_ws_get_products_count)');
			}

			if (Utils::emptyConnection($this->connection)) {
				$this->send_error($this->messages->message_connection_not_found . ' (wps_ws_get_products_count)');
			}

			Utils::wps_access_session();

	    try {

				$response = $this->wps_request(
					'GET',
					$this->get_request_url("/admin/products/count.json"),
					$this->get_request_options()
				);

	      $data = json_decode($response->getBody()->getContents());

	      if (is_object($data) && property_exists($data, 'count')) {

					$this->send_success(['products' => $data->count]);

	      } else {

					$this->send_warning($this->messages->message_products_not_found . ' (wps_ws_get_products_count)');

	      }

	    } catch (\InvalidArgumentException $error) {

				$this->send_error($this->wps_get_error_message($error) . ' (wps_ws_get_products_count)');

	    } catch (RequestException $error) {

				$this->send_error($this->wps_get_error_message($error) . ' (wps_ws_get_products_count)');

	    } catch (ClientException $error) {

				$this->send_error($this->wps_get_error_message($error) . ' (wps_ws_get_products_count)');

	    // Server errors 5xx
	    } catch (ServerException $error) {

				$this->send_error($this->wps_get_error_message($error) . ' (wps_ws_get_products_count)');

	    }

	  }


	  /*

	  Get Collections Count
	  TODO: Move the "No connection details ..." msg into a constant for reusability

	  */
	  public function wps_ws_get_collects_count() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_ws_get_collects_count)');
			}

			if (Utils::emptyConnection($this->connection)) {
				$this->send_error($this->messages->message_connection_not_found . ' (wps_ws_get_collects_count)');
			}


			Utils::wps_access_session();

	    try {

				$response = $this->wps_request(
					'GET',
					$this->get_request_url("/admin/collects/count.json"),
					$this->get_request_options()
				);

	      $data = json_decode($response->getBody()->getContents());

	      if (is_object($data) && property_exists($data, 'count')) {
					$this->send_success(['collects' => $data->count]);

	      } else {
					$this->send_warning($this->messages->message_collects_not_found . ' (wps_ws_get_collects_count)');

	      }

	    } catch (RequestException $error) {

				$this->send_error($this->wps_get_error_message($error) . ' (wps_ws_get_collects_count)');

	    }

	  }


		/*

		Get Smart Collections Count

		*/
		public function wps_ws_get_smart_collections_count() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_ws_get_smart_collections_count)');
			}

			if (Utils::emptyConnection($this->connection)) {
				$this->send_error($this->messages->message_connection_not_found . ' (wps_ws_get_smart_collections_count)');
			}


			Utils::wps_access_session();

			try {

				$response = $this->wps_request(
					'GET',
					$this->get_request_url("/admin/smart_collections/count.json"),
					$this->get_request_options()
				);

				$data = json_decode($response->getBody()->getContents());

				if (is_object($data) && property_exists($data, 'count')) {

					$this->send_success(['smart_collections' => $data->count]);

				} else {

					$this->send_warning($this->messages->message_smart_collections_not_found . ' (wps_ws_get_smart_collections_count)');

				}

			} catch (RequestException $error) {

				$this->send_error($this->wps_get_error_message($error) . ' (wps_ws_get_smart_collections_count)');

			}

		}


		/*

		Get Custom Collections Count

		*/
		public function wps_ws_get_custom_collections_count() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_ws_get_custom_collections_count)');
			}

			if (Utils::emptyConnection($this->connection)) {
				$this->send_error($this->messages->message_connection_not_found . ' (wps_ws_get_custom_collections_count)');
			}


			Utils::wps_access_session();

			try {

				$response = $this->wps_request(
					'GET',
					$this->get_request_url("/admin/custom_collections/count.json"),
					$this->get_request_options()
				);

				$data = json_decode($response->getBody()->getContents());

				if (is_object($data) && property_exists($data, 'count')) {
					$this->send_success(['custom_collections' => $data->count]);

				} else {
					$this->send_warning($this->messages->message_custom_collections_not_found . ' (wps_ws_get_custom_collections_count)');

				}

			} catch (RequestException $error) {

				$this->send_error($this->wps_get_error_message($error) . ' (wps_ws_get_custom_collections_count)');

			}

		}


	  /*

	  Get Orders Count
	  TODO: Combine with other count functions to be more generalized

	  */


	  /*

	  Get Customers Count
	  TODO: Combine with other count functions to be more generalized

	  */


		/*

		Get Shop Count

		*/
		public function wps_ws_get_shop_count() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_ws_get_shop_count)');
			}

			if (Utils::emptyConnection($this->connection)) {
				$this->send_error($this->messages->message_connection_not_found . ' (wps_ws_get_shop_count)');
			}

			$this->send_success(['shop' => 1]);

		}


		/*

		Get Webhooks Count
		Pro only: true

		*/


	  /*

	  Get Shop Data

	  */
	  public function wps_ws_get_shop_data() {

			if (!Utils::valid_backend_nonce($_GET['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_ws_get_shop_data)');
			}

			if (Utils::emptyConnection($this->connection)) {
				$this->send_error($this->messages->message_connection_not_found . ' (wps_ws_get_shop_data)');
			}

	    try {

				$response = $this->wps_request(
					'GET',
					$this->get_request_url("/admin/shop.json"),
					$this->get_request_options()
				);

	      $data = json_decode($response->getBody()->getContents());

	      if (is_object($data) && property_exists($data, 'shop')) {
	        $this->send_success($data);

	      } else {
	        $this->send_warning($this->messages->message_shop_not_found . ' (wps_ws_get_shop_data)');

	      }

	    } catch (RequestException $error) {
	      $this->send_error( $this->wps_get_error_message($error) . ' (wps_ws_get_shop_data)');

	    }

	  }


		/*

		Insert alt text

		*/
		public function wps_insert_alt_text() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_insert_alt_text)');
			}

			$Images = new Images();
			$allImages = $Images->get_all_rows();

			$Guzzle = new Guzzle();


			$requests = function () use ($Guzzle, $allImages) {

				foreach ($allImages as $image) {

					usleep(200000);

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

						// error_log(print_r($response, true));

					});

				}

			};

			$promise = Promise\each_limit(
		    $requests(),
		    3,
		    function($resp) {
					// error_log('---- COMPLETELY DONE -----');
				},
				function($test) {
					// error_log('---- REJECTED DONE -----');
					// error_log(print_r($test, true));
					// error_log('---- / REJECTED DONE -----');
				}
			);

			$promise->wait();

		}


	  /*

	  Get Products + Variants
	  Here we make our requests to the API to insert products and variants

		Runs for each "page" of the Shopify API (250 per page)

	  */
	  public function wps_insert_products_data() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_insert_products_data)');
			}

			if (Utils::emptyConnection($this->connection)) {
				$this->send_error($this->messages->message_connection_not_found . ' (wps_insert_products_data)');
			}

			if (!Utils::isStillSyncing()) {
				$this->send_error($this->messages->message_connection_not_syncing . ' (wps_insert_products_data)');
			}

			$index = 1;
			$insertionResults = [];

			$DB_Settings_General = new Settings_General();
			$DB_Variants = new Variants();
			$DB_Products = new Products();
			$DB_Options = new Options();
			$DB_Images = new Images();
			$DB_Tags = new Tags();
			$Progress = new Progress_Bar(new Config());
			$currentPage = Utils::get_current_page($_POST);

	    try {

				// This is the network requst
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

							$this->send_error($this->messages->message_connection_not_syncing . ': ' . $product->title);
							wp_die();

						}

						// If product is published
		        if (property_exists($product, 'published_at') && $product->published_at !== null) {

							/*

							Insert CPT ...

							*/
					    $insertionResults[$product->title]['cpt'] = $customPostTypeID = CPT::wps_insert_or_update_product($product, $index);

							$product = $DB_Products->modify_product_after_cpt_insert($product, $customPostTypeID);
							$product = $DB_Products->modify_product_before_insert($product, $customPostTypeID);

							/*

							Insert Product ...

							*/
		          $insertionResults[$product->title]['products'] = $DB_Products->insert($product, 'product');


							/*

							Insert tags ...

							*/
							$insertionResults[$product->title]['tags'] = $DB_Tags->insert_product_tags($product, $customPostTypeID);


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

					$this->send_success($insertionResults);


				} else {

					$this->send_warning($this->messages->message_products_not_found . ' (wps_insert_products_data)');

				}

	    } catch (RequestException $error) {

	      $this->send_error( $this->wps_get_error_message($error) . ' (wps_insert_products_data)');

	    }


	  }


	  /*

	  Get Variants
	  TODO: Not currently used

	  */
	  public function wps_ws_get_variants() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_ws_get_variants)');
			}

			if (Utils::emptyConnection($this->connection)) {
				$this->send_error($this->messages->message_connection_not_found . ' (wps_ws_get_variants)');
			}


	    $productID = $_POST['productID'];

	    if (!isset($_POST['currentPage']) || !$_POST['currentPage']) {
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
	          $this->send_success($data);

	        } else {
	          $this->send_warning($this->messages->message_message_variants_not_found . ' (wps_ws_get_variants)');

	        }

	      } catch (RequestException $error) {

	        $this->send_error( $this->wps_get_error_message($error) . ' (wps_ws_get_variants)');

	      }

	    }

	  }


	  /*

	  Get Collections

	  */
	  public function wps_insert_custom_collections_data() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_insert_custom_collections_data)');
			}

			if (Utils::emptyConnection($this->connection)) {
				$this->send_error($this->messages->message_connection_not_found . ' (wps_insert_custom_collections_data)');
			}

			if (!Utils::isStillSyncing()) {
				$this->send_error($this->messages->message_connection_not_syncing . ' (wps_insert_custom_collections_data)');
			}


			if (!isset($_POST['currentPage']) || !$_POST['currentPage']) {
	      $currentPage = 1;

	    } else {
	      $currentPage = $_POST['currentPage'];
	    }


	    try {

	      $DB_Images = new Images();
	      $DB_Collections_Custom = new Collections_Custom();


				$response = $this->wps_request(
					'GET',
					$this->get_request_url("/admin/custom_collections.json", "?limit=250&page=" . $currentPage, "?limit=250&page=" . $currentPage),
					$this->get_request_options()
				);

	      $data = json_decode($response->getBody()->getContents());


	      if (property_exists($data, "custom_collections")) {

	        $results = $DB_Collections_Custom->insert_custom_collections($data->custom_collections);

					if (empty($results)) {
						$this->send_warning($this->messages->message_insert_custom_collections_error . ' (wps_insert_custom_collections_data)');

					} else {
						$this->send_success($results);
					}


	      } else {
	        $this->send_warning($this->messages->message_custom_collections_not_found . ' (wps_insert_custom_collections_data)');

	      }

	    } catch (RequestException $error) {

	      $this->send_error( $this->wps_get_error_message($error) . ' (wps_insert_custom_collections_data)');

	    }

		}


	  /*

	  TODO: NEED TESTING BELOW THIS FUNCTION

	  Get Collections

	  */
	  public function wps_insert_smart_collections_data() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_insert_smart_collections_data)');
			}

			if (Utils::emptyConnection($this->connection)) {
				$this->send_error($this->messages->message_connection_not_found . ' (wps_insert_smart_collections_data)');
			}

			if (!Utils::isStillSyncing()) {
				$this->send_error($this->messages->message_connection_not_syncing . ' (wps_insert_smart_collections_data)');
			}


			Utils::prevent_timeouts();


			if (!isset($_POST['currentPage']) || !$_POST['currentPage']) {
	      $currentPage = 1;

	    } else {
	      $currentPage = $_POST['currentPage'];
	    }


	    try {

	      $DB_Images = new Images();
	      $DB_Collections_Smart = new Collections_Smart();

				$response = $this->wps_request(
					'GET',
					$this->get_request_url("/admin/smart_collections.json", "?limit=250&page=" . $currentPage),
					$this->get_request_options()
				);

	      $data = json_decode($response->getBody()->getContents());

	      if (property_exists($data, "smart_collections")) {

	        $results = $DB_Collections_Smart->insert_smart_collections($data->smart_collections);

					if (empty($results)) {
						$this->send_error($this->messages->message_insert_smart_collections_error . ' (wps_insert_smart_collections_data)');

					} else {
						$this->send_success($results);
					}


	      } else {
	        $this->send_warning($this->messages->message_smart_collections_not_found . ' (wps_insert_smart_collections_data)');
	      }

	    } catch (RequestException $error) {
	      $this->send_error( $this->wps_get_error_message($error) . ' (wps_insert_smart_collections_data)');

	    }

		}


	  /*

	  Get products from collection

	  */
	  public function wps_ws_get_products_from_collection() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_ws_get_products_from_collection)');
			}

			if (Utils::emptyConnection($this->connection)) {
				$this->send_error($this->messages->message_connection_not_found . ' (wps_ws_get_products_from_collection)');
			}


	    try {

	      $collectionID = $_POST['collectionID'];

				$response = $this->wps_request(
					'GET',
					$this->get_request_url("/admin/products.json", "?collection_id=" . $collectionID),
					$this->get_request_options()
				);

	      $data = json_decode($response->getBody()->getContents());

	      if (property_exists($data, 'products')) {
	        $this->send_success($data->products);

	      } else {
	        $this->send_warning($this->messages->message_products_from_collection_not_found . ' (wps_ws_get_products_from_collection)');

	      }

	    } catch (RequestException $error) {

	      $this->send_error( $this->wps_get_error_message($error) . ' (wps_ws_get_products_from_collection)');

	    }

	  }


	  /*

	  Get a list of collects by product ID

	  */
	  public function wps_insert_collects() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_insert_collects)');
			}

			if (Utils::emptyConnection($this->connection)) {
				$this->send_error($this->messages->message_connection_not_found . ' (wps_insert_collects)');
			}

			if (!Utils::isStillSyncing()) {
				$this->send_error($this->messages->message_connection_not_syncing . ' (wps_insert_collects)');
			}


	    try {

	      $DB_Collects = new Collects();

	      if (!isset($_POST['currentPage']) || !$_POST['currentPage']) {
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

					$resultCollects = $DB_Collects->insert_collects($data->collects);

					if (empty($resultCollects)) {
						$this->send_error($this->messages->message_insert_collects_error . ' (wps_insert_collects)');

					} else {

						$this->send_success($resultCollects);
					}

	      } else {

	        $this->send_warning($this->messages->message_insert_collects_error_missing . ' (wps_insert_collects)');

	      }

	    } catch (RequestException $error) {

	      $this->send_error( $this->wps_get_error_message($error) . ' (wps_insert_collects)');

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

			if (Utils::emptyConnection($this->connection)) {
			  $this->send_error($this->messages->message_connection_not_found . ' (wps_ws_get_collects_from_product)');
			}

	    if ($ajax) {

				if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				  $this->send_error($this->messages->message_nonce_invalid . ' (wps_ws_get_collects_from_product)');
				}

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
	          $this->send_success($data);

	        } else {
	          return $data;

	        }

	      } else {
	        $this->send_warning($this->messages->message_collects_not_found . ' (wps_ws_get_collects_from_product)');

	      }

	    } catch (RequestException $error) {

	      $this->send_error( $this->wps_get_error_message($error) . ' (wps_ws_get_collects_from_product)');

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



			if (Utils::emptyConnection($this->connection)) {
			  $this->send_error($this->messages->message_connection_not_found . ' (wps_ws_get_collects_from_collection)');
			}


	    if ($ajax) {

				if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				  $this->send_error($this->messages->message_nonce_invalid . ' (wps_ws_get_collects_from_collection)');
				}

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

	      $response = $this->wps_get_error_message($error) . ' (wps_ws_get_collects_from_collection)';

	    }


	    if ($ajax) {

	      if (property_exists($response, 'errors')) {
					$this->send_error($data->errors);

	      } else {
	        $this->send_success($response);
	      }

	    } else {
	      return $response;

	    }


	  }


	  /*

	  Get single collection

	  */
	  public function wps_ws_get_single_collection() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			  $this->send_error($this->messages->message_nonce_invalid . ' (wps_ws_get_single_collection)');
			}

			if (Utils::emptyConnection($this->connection)) {
			  $this->send_error($this->messages->message_connection_not_found . ' (wps_ws_get_single_collection)');
			}


	    try {

				$response = $this->wps_request(
					'GET',
					$this->get_request_url("/admin/custom_collections/" . $_POST['collectionID'] . ".json"),
					$this->get_request_options()
				);

	      $data = json_decode($response->getBody()->getContents());

	      if (property_exists($data, 'custom_collection')) {
	        $this->send_success($data);

	      } else {
	        $this->send_warning($this->messages->message_custom_collections_not_found . ' (wps_ws_get_single_collection)');

	      }

	    } catch (RequestException $error) {

	      $this->send_error( $this->wps_get_error_message($error) . ' (wps_ws_get_single_collection)');

	    }

	  }


	  /*

	  Invalidate the Shopify API connection

	  */
	  public function wps_ws_end_api_connection() {

	    if (Utils::emptyConnection($this->connection)) {
	      return new \WP_Error('error', $this->messages->message_connection_disconnect_invalid_access_token . ' (wps_ws_end_api_connection)');

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

	        return new \WP_Error('error', $this->wps_get_error_message($error) . ' (wps_ws_end_api_connection)');

	      }

	    }

	  }


		/*

		Attaches all webhooks
		Pro only: true

		*/



	  /*

		Get Webhooks
		Pro only: true

		*/


	  /*

	  Get Progress Count

	  */
	  function wps_get_progress_count() {
	    $this->send_success($_SESSION);
	  }


	  /*

	  Update Settings General

	  */
	  public function wps_update_settings_general() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			  $this->send_error($this->messages->message_nonce_invalid . ' (wps_update_settings_general)');
			}

	    global $wp_rewrite;

	    $DB_Settings_General = new Settings_General();

	    $newGeneralSettings = array();

	    if (isset($_POST['wps_settings_general_products_url']) && $_POST['wps_settings_general_products_url']) {
	      $newGeneralSettings['url_products'] = $_POST['wps_settings_general_products_url'];
	    }

	    if (isset($_POST['wps_settings_general_collections_url']) && $_POST['wps_settings_general_collections_url']) {
	      $newGeneralSettings['url_collections'] = $_POST['wps_settings_general_collections_url'];
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

			if (isset($_POST['wps_settings_general_products_link_to_shopify'])) {
				$newGeneralSettings['products_link_to_shopify'] = (int)$_POST['wps_settings_general_products_link_to_shopify'];
			}

			if (isset($_POST['wps_settings_general_show_breadcrumbs'])) {
				$newGeneralSettings['show_breadcrumbs'] = (int)$_POST['wps_settings_general_show_breadcrumbs'];
			}

			if (isset($_POST['wps_settings_general_hide_pagination'])) {
				$newGeneralSettings['hide_pagination'] = (int)$_POST['wps_settings_general_hide_pagination'];
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


			// Always 1 if free version
			if (isset($_POST['wps_settings_general_selective_sync_all'])) {
				$newGeneralSettings['selective_sync_all'] = (int)$_POST['wps_settings_general_selective_sync_all'];

			} else {
				$newGeneralSettings['selective_sync_all'] = 1;
			}


			if (isset($_POST['wps_settings_general_selective_sync_products'])) {
				$newGeneralSettings['selective_sync_products'] = (int)$_POST['wps_settings_general_selective_sync_products'];
			}

			if (isset($_POST['wps_settings_general_selective_sync_collections'])) {
				$newGeneralSettings['selective_sync_collections'] = (int)$_POST['wps_settings_general_selective_sync_collections'];
			}


			if (isset($_POST['wps_settings_general_selective_sync_tags'])) {
				$newGeneralSettings['selective_sync_tags'] = (int)$_POST['wps_settings_general_selective_sync_tags'];
			}

			if (isset($_POST['wps_settings_general_selective_sync_images'])) {
				$newGeneralSettings['selective_sync_images'] = (int)$_POST['wps_settings_general_selective_sync_images'];
			}

			if (isset($_POST['wps_settings_general_selective_sync_shop'])) {
				$newGeneralSettings['selective_sync_shop'] = (int)$_POST['wps_settings_general_selective_sync_shop'];
			}




			if (isset($_POST['wps_settings_general_related_products_show'])) {
				$newGeneralSettings['related_products_show'] = (int)$_POST['wps_settings_general_related_products_show'];
			}

			if (isset($_POST['wps_settings_general_related_products_sort'])) {
				$newGeneralSettings['related_products_sort'] = $_POST['wps_settings_general_related_products_sort'];
			}

			if (isset($_POST['wps_settings_general_related_products_amount'])) {
				$newGeneralSettings['related_products_amount'] = (int)$_POST['wps_settings_general_related_products_amount'];
			}



			/*

			If user keeps all selective sync fields empty, default to sync all.
			TODO: Handle on front-end instead

			*/
			if ($newGeneralSettings['selective_sync_all'] === 0 && $newGeneralSettings['selective_sync_products'] === 0 && $newGeneralSettings['selective_sync_collections'] === 0 && $newGeneralSettings['selective_sync_customers'] === 0 && $newGeneralSettings['selective_sync_orders'] === 0 && $newGeneralSettings['selective_sync_shop'] === 0) {
				$newGeneralSettings['selective_sync_all'] = 1;
			}


			/*

			If user keeps all stylesheet fields empty, default to all styles.
			TODO: Handle on front-end instead

			*/
			if ($newGeneralSettings['styles_all'] === 0 && $newGeneralSettings['styles_core'] === 0 && $newGeneralSettings['styles_grid'] === 0) {
				$newGeneralSettings['styles_all'] = 1;
			}


	    $results = $DB_Settings_General->update_general($newGeneralSettings);

	    Transients::delete_cached_settings();
	    set_transient('wps_settings_updated', $newGeneralSettings);

	    $this->send_success($results);

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

			if (!Utils::valid_backend_nonce($_GET['nonce'])) {
			  $this->send_error($this->messages->message_nonce_invalid . ' (wps_get_connection)');
			}

	    if (get_transient('wps_settings_connection')) {
	      $connectionData = get_transient('wps_settings_connection');

	    } else {

	      $DB_Settings_Connection = new Settings_Connection();
	      $connectionData = $DB_Settings_Connection->get();
	      set_transient('wps_settings_connection', $connectionData);

	    }

	    $this->send_success($connectionData);

	  }


		/*

		Reset rewrite rules on CTP url change

		*/
		public function wps_remove_connection() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			  $this->send_error($this->messages->message_nonce_invalid . ' (wps_remove_connection)');
			}

			$response_connection_settings = $this->wps_delete_settings_connection();

			if (is_wp_error($response_connection_settings)) {
				$this->send_error($response_connection_settings->get_error_message()  . ' (wps_remove_connection)');

			} else {
				$this->send_success($response_connection_settings);
			}

		}


	  /*

	  Insert connection data

	  */
	  public function wps_insert_connection() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_insert_connection)');
			}

			if (!Utils::isStillSyncing()) {
				$this->send_error($this->messages->message_connection_not_syncing . ' (wps_insert_connection)');
			}

	    $DB_Settings_Connection = new Settings_Connection();
	    $connectionData = $_POST['connectionData'];
	    $connectionData = (array) $connectionData;

	    $results = $DB_Settings_Connection->insert_connection($connectionData);

	    if ($results === false) {
	      $this->send_error($this->messages->message_connection_save_error . ' (wps_insert_connection)');

	    } else {
	      $this->send_success($results);

	    }

	  }


	  /*

	  Insert Shop Data

	  */
	  public function wps_insert_shop() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_insert_shop)');
			}

			if (!Utils::isStillSyncing()) {
				$this->send_error($this->messages->message_connection_not_syncing . ' (wps_insert_shop)');
			}

			$DB_Shop = new Shop();
	    $shopData = $_POST['shopData'];

	    $results = $DB_Shop->insert_shop($shopData);

			if (empty($results)) {
				$this->send_error($this->message_connection_save_error . ' (wps_insert_shop)');

			} else {
				$this->send_success($results);
			}


	  }


	  /*

	  Delete Shop Data

	  */
	  public function wps_delete_shop() {

			$DB_Shop = new Shop();
			$DB_Settings_General = new Settings_General();

			$syncStates = $DB_Settings_General->selective_sync_status(); // This property was not set for users ...

			if ($syncStates['all']) {

				if ( !$DB_Shop->delete() ) {
		      return new \WP_Error('error', $this->messages->message_delete_shop_error . ' (wps_delete_shop)');

		    } else {
		      return true;
		    }

			} else {

				if ($syncStates['shop']) {

					if ( !$DB_Shop->delete() ) {
			      return new \WP_Error('error', $this->messages->message_delete_shop_error . ' (wps_delete_shop)');

			    } else {
			      return true;
			    }

				} else {
					return true;
				}

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
	      return new \WP_Error('error', $this->messages->message_delete_connection_error . ' (wps_delete_settings_connection)');

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


			if (!$Backend->wps_delete_taxonomies('wps_tags')) {
				$result = new \WP_Error('error', $this->messages->message_delete_cpt_products_error . ' (wps_delete_synced_data_tagggs)');
			}

	    if (!$Backend->wps_delete_posts('wps_products')) {
	      $result = new \WP_Error('error', $this->messages->message_delete_cpt_products_error . ' (wps_delete_synced_data)');
	    }

	    if (!$Backend->wps_delete_posts('wps_collections')) {
	      $result = new \WP_Error('error', $this->messages->message_delete_cpt_collections_error . ' (wps_delete_synced_data)');
	    }

	    return $result;

	  }


	  /*

	  wps_delete_images

	  */
	  public function wps_delete_images() {

			$Images = new Images();
			$DB_Settings_General = new Settings_General();
			$syncStates = $DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$Images->delete()) {
					return new \WP_Error('error', $this->messages->message_delete_product_images_error . ' (wps_delete_images)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['products']) {

					if (!$Images->delete()) {
						return new \WP_Error('error', $this->messages->message_delete_product_images_error . ' (wps_delete_images 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

	  }


	  /*

	  wps_delete_inventory

	  */
	  public function wps_delete_inventory() {

	    $Inventory = new Inventory();

	    if (!$Inventory->delete()) {
	      return new \WP_Error('error', $this->messages->message_delete_product_inventory_error . ' (wps_delete_inventory)');

	    } else {
	      return true;
	    }

	  }


	  /*

	  wps_delete_collects

	  */
	  public function wps_delete_collects() {

	    $Collects = new Collects();
			$DB_Settings_General = new Settings_General();
			$syncStates = $DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$Collects->delete()) {
		      return new \WP_Error('error', $this->messages->message_delete_collects_error . ' (wps_delete_collects)');

		    } else {
		      return true;
		    }

			} else {

				if ($syncStates['products']) {

					if (!$Collects->delete()) {
			      return new \WP_Error('error', $this->messages->message_delete_collects_error . ' (wps_delete_collects 2)');

			    } else {
			      return true;
			    }

				} else {
					return true;
				}

			}

	  }


	  /*

	  wps_delete_options

	  */
	  public function wps_delete_tags() {

			$Tags = new Tags();
			$DB_Settings_General = new Settings_General();
			$syncStates = $DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$Tags->delete()) {
					return new \WP_Error('error', $this->messages->message_delete_product_tags_error . ' (wps_delete_tags)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['products']) {

					if (!$Tags->delete()) {
						return new \WP_Error('error', $this->messages->message_delete_product_tags_error . ' (wps_delete_tags 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

	  }


	  /*

	  wps_delete_options

	  */
	  public function wps_delete_options() {

			$Options = new Options();
			$DB_Settings_General = new Settings_General();
			$syncStates = $DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$Options->delete()) {
					return new \WP_Error('error', $this->messages->message_delete_product_options_error . ' (wps_delete_options)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['products']) {

					if (!$Options->delete()) {
						return new \WP_Error('error', $this->messages->message_delete_product_options_error . ' (wps_delete_options 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

	  }


	  /*

	  wps_delete_variants

	  */
	  public function wps_delete_variants() {

			$Variants = new Variants();
			$DB_Settings_General = new Settings_General();
			$syncStates = $DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$Variants->delete()) {
					return new \WP_Error('error', $this->messages->message_delete_product_variants_error . ' (wps_delete_variants)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['products']) {

					if (!$Variants->delete()) {
						return new \WP_Error('error', $this->messages->message_delete_product_variants_error . ' (wps_delete_variants 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

	  }


	  /*

	  wps_delete_products

	  */
	  public function wps_delete_products() {

			$Products = new Products();
			$DB_Settings_General = new Settings_General();
			$syncStates = $DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$Products->delete()) {
					return new \WP_Error('error', $this->messages->message_delete_products_error . ' (wps_delete_products)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['products']) {

					if (!$Products->delete()) {
						return new \WP_Error('error', $this->messages->message_delete_products_error . ' (wps_delete_products 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

	  }


	  /*

	  wps_delete_products

	  */
	  public function wps_delete_custom_collections() {

			$Collections_Custom = new Collections_Custom();
			$DB_Settings_General = new Settings_General();
			$syncStates = $DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$Collections_Custom->delete()) {
					return new \WP_Error('error', $this->messages->message_delete_custom_collections_error . ' (wps_delete_custom_collections)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['custom_collections']) {

					if (!$Collections_Custom->delete()) {
						return new \WP_Error('error', $this->messages->message_delete_custom_collections_error . ' (wps_delete_custom_collections 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

	  }


	  /*

	  wps_delete_products

	  */
	  public function wps_delete_smart_collections() {

			$Collections_Smart = new Collections_Smart();
			$DB_Settings_General = new Settings_General();
			$syncStates = $DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$Collections_Smart->delete()) {
					return new \WP_Error('error', $this->messages->message_delete_smart_collections_error . ' (wps_delete_smart_collections)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['smart_collections']) {

					if (!$Collections_Smart->delete()) {
						return new \WP_Error('error', $this->messages->message_delete_smart_collections_error . ' (wps_delete_smart_collections 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

	  }


	  /*

	  wps_delete_orders

	  */


	  /*

	  wps_delete_customers

	  */


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
	      Utils::valid_uninstall_nonce($_POST['nonce']) ?: $this->send_error($this->messages->message_nonce_invalid . ' (wps_uninstall_consumer)');
	    }

	    $results = array();
			$License = new License($this->config);
	    $Transients = new Transients();
	    $DB_Settings_Connection = new Settings_Connection();
	    $connection = $DB_Settings_Connection->get_column_single('api_key');


			/*

			Step 1. Remove all product data including CPTs

			*/
			$results = $this->wps_uninstall_all_data(false);


	    if (!empty($connection)) {

				/*

				Step 2. Remove & Deactivate any license keys

				*/
				$response_license = $License->wps_deactivate_plugin_license();

				if (is_wp_error($response_license)) {
					$results['connection_settings'] = $response_license->get_error_message()  . ' (wps_uninstall_consumer)';

				} else {
					$results['connection_settings'] = $response_license;
				}


	    }


	    if ($ajax) {
	      $this->send_success($results);

	    } else {
	      return $results;

	    }


	  }


		/*

		Remove all data
		Returns: results object

		*/
		public function wps_uninstall_all_data($ajax = true) {

			if ($ajax) {

				if (!Utils::valid_backend_nonce($_POST['nonce'])) {
					$this->send_error($this->messages->message_nonce_invalid . ' (wps_uninstall_all_data)');
				}

			}

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


			if ($ajax) {
				$this->send_success($results);

			} else {
				return $results;
			}

		}


	  /*

	  Uninstall product-related data
	  Returns: Response object

	  */
	  public function wps_uninstall_product_data($ajax = true) {

			$results = array();
			$Transients = new Transients();
			$DB_Settings_Connection = new Settings_Connection();
			$connection = $DB_Settings_Connection->get_column_single('domain');


	    if ($_POST['action'] === 'wps_uninstall_product_data') {
	      $ajax = true;

	    } else {
	      $ajax = false;
	    }

	    if ($ajax) {

				if (!Utils::valid_backend_nonce($_POST['nonce'])) {
					$this->send_error($this->messages->message_nonce_invalid . ' (wps_uninstall_product_data)');
				}


	    }

			Utils::wps_access_session();


	    /*

	    Remove Shop

	    */
	    $response_shop = $this->wps_delete_shop();

	    if (is_wp_error($response_shop)) {
	      $results['shop'] = $response_shop->get_error_message()  . ' (wps_uninstall_product_data)';

	    } else {
	      $results['shop'] = $response_shop;
	    }


			/*

			Remove Webhooks

			*/

	    /*

	    Remove Products

	    */
	    $response_products = $this->wps_delete_products();

	    if (is_wp_error($response_products)) {
	      $results['products'] = $response_products->get_error_message()  . ' (wps_uninstall_product_data)';

	    } else {
	      $results['products'] = $response_products;
	    }


	    /*

	    Remove Custom Collections

	    */
	    $response_collections_custom = $this->wps_delete_custom_collections();

	    if (is_wp_error($response_collections_custom)) {
	      $results['custom_collections'] = $response_collections_custom->get_error_message()  . ' (wps_uninstall_product_data)';

	    } else {
	      $results['custom_collections'] = $response_collections_custom;
	    }


	    /*

	    Remove Smart Collections

	    */
	    $response_collections_smart = $this->wps_delete_smart_collections();

	    if (is_wp_error($response_collections_smart)) {
	      $results['smart_collections'] = $response_collections_smart->get_error_message()  . ' (wps_uninstall_product_data)';

	    } else {
	      $results['smart_collections'] = $response_collections_smart;
	    }


	    /*

	    Remove Collects

	    */
	    $response_collects = $this->wps_delete_collects();

	    if (is_wp_error($response_collects)) {
	      $results['collects'] = $response_collects->get_error_message()  . ' (wps_uninstall_product_data)';

	    } else {
	      $results['collects'] = $response_collects;
	    }


	    /*

	    Remove Variants

	    */
	    $response_variants = $this->wps_delete_variants();

	    if (is_wp_error($response_variants)) {
	      $results['variants'] = $response_variants->get_error_message()  . ' (wps_uninstall_product_data)';

	    } else {
	      $results['variants'] = $response_variants;
	    }


	    /*

	    Remove Options

	    */
	    $response_options = $this->wps_delete_options();

	    if (is_wp_error($response_options)) {
	      $results['options'] = $response_options->get_error_message()  . ' (wps_uninstall_product_data)';

	    } else {
	      $results['options'] = $response_options;
	    }


	    /*

	    Remove Tags

	    */
	    $response_tags = $this->wps_delete_tags();

	    if (is_wp_error($response_tags)) {
	      $results['tags'] = $response_tags->get_error_message()  . ' (wps_uninstall_product_data)';

	    } else {
	      $results['tags'] = $response_tags;
	    }


	    /*

	    Remove Images

	    */
	    $response_images = $this->wps_delete_images();

	    if (is_wp_error($response_images)) {
	      $results['images'] = $response_images->get_error_message()  . ' (wps_uninstall_product_data)';

	    } else {
	      $results['images'] = $response_images;
	    }


	    /*

	    Remove Orders

	    */

	    /*

	    Remove Customers

	    */

	    /*

	    Remove Transients

	    */
	    $response_transients = $Transients->delete_all_cache();

	    if (is_wp_error($response_transients)) {
	      $results['transients'] = $response_transients->get_error_message()  . ' (wps_uninstall_product_data)';

	    } else {
	      $results['transients'] = $response_transients;
	    }


	    if ($ajax) {

	      $this->send_success($results);

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
	      $this->send_success($results);

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

			if (!Utils::valid_cache_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid  . ' (wps_clear_cache)');
			}

	    $Transients = new Transients();

			set_transient('wps_settings_updated', true);
			set_transient('wps_recently_connected', true);

	    $results = $Transients->delete_all_cache();

	    if (is_wp_error($results)) {
	      $this->send_error(esc_html__($results->get_error_message()  . ' (wps_clear_cache)', 'wp-shopify'));

	    } else {

				flush_rewrite_rules();
	      $this->send_success($results);

	    }

	  }


	  /*

	  Sync with CPT

	  CURRENTLY NOT USED

	  */
	  public function wps_sync_with_cpt() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (wps_sync_with_cpt)');
			}


	    $Utils = new Utils();
	    $DB_Products = new Products();
	    $products = $DB_Products->get_products();
	    $results = array();


	    foreach ($products as $product) {
	      $results[] = $DB_Products->update_post_content_if_changed($product);
	    }

	    $filteredResults = array_filter($results, array($Utils, 'filter_errors'));

	    if (!empty($filteredResults)) {

	      $filteredResults = array_map(
	        array($Utils, 'filter_errors_with_messages'),
	        array_keys($filteredResults),
	        $filteredResults
	      );

	      $this->send_error($filteredResults);

	    } else {
	      $this->send_success($results);

	    }

	  }


	  /*

	  Insert Orders

	  */


	  /*

	  Insert Customers

	  */


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


			if (is_object($this->connection) && isset($this->connection->api_key)) {
				$authToken = base64_encode($this->connection->api_key . ':' . $this->connection->password);

			} else {
				$authToken = '';
			}


			if ($shopify) {

				$finalOptions = [
					'http_errors' => true,
					'headers' => [
						'Authorization' => 'Basic ' . $authToken
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

			if (is_object($this->connection) && isset($this->connection->domain)) {
				return "https://" . $this->connection->domain . $endpoint . $params;
			}

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

				return $Guzzle->request(
					$method,
					$url,
					$options
				);

			}

		}


		/*

		Save Counts

		*/
		public function save_counts() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (save_counts)');
			}

			if (Utils::emptyConnection($this->connection)) {
				$this->send_error($this->messages->message_connection_not_found . ' (save_counts)');
			}


			if (isset($_POST['counts']) && $_POST['counts']) {
				$counts = Utils::shift_arrays_up($_POST['counts']);

			} else {
				$counts = [];
			}


			if (!empty($counts)) {

				Utils::wps_access_session();

				if (isset($counts['smart_collections'])) {
					$_SESSION['wps_syncing_totals']['smart_collections'] = $counts['smart_collections'];
				}

				if (isset($counts['custom_collections'])) {
					$_SESSION['wps_syncing_totals']['custom_collections'] = $counts['custom_collections'];
				}

				if (isset($counts['products'])) {
					$_SESSION['wps_syncing_totals']['products'] = $counts['products'];
				}

				if (isset($counts['collects'])) {
					$_SESSION['wps_syncing_totals']['collects'] = $counts['collects'];
				}


				Utils::wps_close_session_write();

				$this->send_success($counts);

			} else {
				$this->send_error('Nothing to sync!');
			}

		}


		/*

		Get Total Counts

		*/
		public function get_total_counts() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->messages->message_nonce_invalid . ' (get_total_counts)');
			}

			Utils::wps_access_session();

			$this->send_success($_SESSION['wps_syncing_totals']);

		}




		/*

		Mock: Shopify error wrapper

		*/
		public function mock_shopify_error($status) {

			$mock = new MockHandler([
				new Response($status, [])
			]);

			$handler = HandlerStack::create($mock);

			$client = new Guzzle(['handler' => $handler]);

			return $client->request('GET', '/');

		}

	}

}
