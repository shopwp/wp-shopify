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
use WPS\Transients;
use WPS\Messages;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;


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
    sleep(10);
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

      return esc_html__($errorMessage, 'wp-shopify');

    } else {

      return esc_html__($error->getMessage(), 'wp-shopify');

    }

  }


  /*

  Callback to the on_headers Guzzle function

  */
  public function wps_ws_check_rate_limit($response) {

    if ($this->wps_ws_get_shopify_api_call_amount($response) === '39/40') {
      $this->wps_ws_throttle_requests();
    }

  }


  /*

  Get Variants
  TODO: Not currently used

  */
  public function wps_ws_get_image_alt($image) {

    if (Utils::emptyConnection($this->connection)) {
      wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1046a)');

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

          $url = "https://" . $this->connection->domain . "/admin/metafields.json?metafield[owner_id]=" . $imageID . "&metafield[owner_resource]=product_image&limit=250";

          $headers = array(
            'X-Shopify-Access-Token' => $this->connection->access_token
          );

          $Guzzle = new Guzzle();
          $guzzelResponse = $Guzzle->request('GET', $url, array(
            'headers' => $headers,
            'on_headers' => function(ResponseInterface $response) {
                $this->wps_ws_check_rate_limit($response);
              }
          ));

          if (!is_object($guzzelResponse)) {
            return;
          }

          $data = json_decode($guzzelResponse->getBody()->getContents());

          if (property_exists($data, 'metafields')) {

            if (is_array($data->metafields) && !empty($data->metafields)) {
              return $data->metafields[0]->value;

            } else {
              return esc_html__('Shop Product', 'wp-shopify'); // Default alt text if none exists
            }

          } else {
            return new \WP_Error('error', $data->errors);

          }

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

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1045a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1045b)');

    try {

      $url = "https://" . $this->connection->domain . "/admin/products/count.json";

      // Unit test URL
      // $url = 'http://www.mocky.io/v2/5a2486a52e0000ca1083bfc3';

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $productsCountResponse = json_decode($guzzelResponse->getBody()->getContents());

      if (is_object($productsCountResponse) && property_exists($productsCountResponse, 'count')) {
        wp_send_json_success($productsCountResponse);

      } else {
        wp_send_json_error($productsCountResponse);
      }

    } catch (\InvalidArgumentException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1045c)');

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1045d)');

    } catch (ClientException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1045e)');

    // Server errors 5xx
    } catch (ServerException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1045f)');

    }

  }


  /*

  Get Collections Count
  TODO: Move the "No connection details ..." msg into a constant for reusability

  */
  public function wps_ws_get_collects_count() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1044a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1044b)');

    try {

      $url = "https://" . $this->connection->domain . "/admin/collects/count.json";

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $collectionsCountResponse = json_decode($guzzelResponse->getBody()->getContents());

      if (is_object($collectionsCountResponse) && property_exists($collectionsCountResponse, 'count')) {
        wp_send_json_success($collectionsCountResponse);

      } else {
        wp_send_json_error($collectionsCountResponse);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1044c)');

    }

  }


  /*

  Get Orders Count
  TODO: Combine with other count functions to be more generalized

  */
  public function wps_ws_get_orders_count() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1043a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1043b)');

    try {

      $url = "https://" . $this->connection->domain . "/admin/orders/count.json";

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $countResponse = json_decode($guzzelResponse->getBody()->getContents());

      if (is_object($countResponse) && property_exists($countResponse, 'count')) {
        wp_send_json_success($countResponse);

      } else {
        wp_send_json_error($countResponse);

      }

    } catch (RequestException $error) {
      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1043c)');

    }

  }


  /*

  Get Customers Count
  TODO: Combine with other count functions to be more generalized

  */
  public function wps_ws_get_customers_count() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1042a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1042b)');

    try {

      $url = "https://" . $this->connection->domain . "/admin/customers/count.json";

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $countResponse = json_decode($guzzelResponse->getBody()->getContents());

      if (is_object($countResponse) && property_exists($countResponse, 'count')) {
        wp_send_json_success($countResponse);

      } else {
        wp_send_json_error($countResponse);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1042c)');

    }

  }


  /*

  Get Shop Data

  */
  public function wps_ws_get_shop_data() {

    Utils::valid_backend_nonce($_GET['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1041a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1041b)');

    try {

      $url = "https://" . $this->connection->domain . "/admin/shop.json";

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $shopDataResponse = json_decode($guzzelResponse->getBody()->getContents());

      if (is_object($shopDataResponse) && property_exists($shopDataResponse, 'shop')) {
        wp_send_json_success($shopDataResponse);

      } else {
        wp_send_json_error($shopDataResponse);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1041c)');

    }

  }


  /*

  Get Products + Variants

  Here we make our requests to the API to insert products and variants

  */
  public function wps_insert_products_data() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1040a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1040b)');

    try {

      $insertionResults = array();

      $DB_Variants = new Variants();
      $DB_Products = new Products();
      $DB_Options = new Options();
      $DB_Images = new Images();

      if(!isset($_POST['currentPage']) || !$_POST['currentPage']) {
        $currentPage = 1;

      } else {
        $currentPage = $_POST['currentPage'];
      }

      $url = "https://" . $this->connection->domain . "/admin/products.json?limit=250&page=" . $currentPage;

      /*

      If Access Token is expired or wrong the follow error will result:
      "[API] Invalid API key or access token (unrecognized login or wrong password)"

      */
      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );


      /*

      For testing server timeout error, add these to guzzle settings ...

      'timeout' => 0.5,
      'connect_timeout' => 0.5,

      */
      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $data = json_decode($guzzelResponse->getBody()->getContents());

      if (is_object($data) && property_exists($data, 'products')) {

        /*

        This is where the bulk of product data is inserted into the database. The
        "insert_products" method inserts both the CPT's and custom WPS table data.

        */
        $resultProducts = $DB_Products->insert_products( $data->products );

        if (empty($resultProducts)) {
          wp_send_json_error($this->messages->message_syncing_products_error . ' (Error code: #1040c)');
        }

        $resultVariants = $DB_Variants->insert_variants( $data->products );

        if (empty($resultVariants)) {
          wp_send_json_error($this->messages->message_syncing_variants_error . ' (Error code: #1040d)');
        }

        $resultOptions = $DB_Options->insert_options( $data->products );

        if (empty($resultOptions)) {
          wp_send_json_error($this->messages->message_syncing_options_error . ' (Error code: #1040e)');
        }


        /*

        Gets Alt text from Shopify. Will stop immediately if error occurs.

        */
        $resultImages = $DB_Images->insert_images( $data->products );

        if (is_wp_error($resultImages)) {
          wp_send_json_error(esc_html__($resultImages->get_error_message() . ' (Error code: #1040f)', 'wp-shopify'));
        }


        $insertionResults['products'] = $resultProducts;
        $insertionResults['variants'] = $resultVariants;
        $insertionResults['options'] = $resultOptions;
        $insertionResults['images'] = $resultImages;

        wp_send_json_success($insertionResults);

      } else {
        wp_send_json_error($data->errors);

      }


    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1040g)');

    }


  }


  /*

  Get Variants
  TODO: Not currently used

  */
  public function wps_ws_get_variants() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1039a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1039b)');

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

        $url = "https://" . $this->connection->domain . "/admin/products/" . $productID . "/variants.json?limit=250&page=" . $currentPage;

        $headers = array(
          'X-Shopify-Access-Token' => $this->connection->access_token
        );

        $Guzzle = new Guzzle();
        $guzzelResponse = $Guzzle->request('GET', $url, array(
          'headers' => $headers
        ));

        $data = json_decode($guzzelResponse->getBody()->getContents());

        if (property_exists($data, 'variants')) {
          wp_send_json_success($data);

        } else {
          wp_send_json_error($data->errors);

        }

      } catch (RequestException $error) {

        wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1039c)');

      }

    }

  }


  /*

  Get Collections

  */
  public function wps_insert_custom_collections_data() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1038a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1038b)');

    try {

      $DB_Images = new Images();
      $DB_Collections_Custom = new Collections_Custom();

      $url = "https://" . $this->connection->domain . "/admin/custom_collections.json";

  		$headers = array(
  			'X-Shopify-Access-Token' => $this->connection->access_token
  		);

      $Guzzle = new Guzzle();

      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $data = json_decode($guzzelResponse->getBody()->getContents());

      if (property_exists($data, "custom_collections")) {

        $results = $DB_Collections_Custom->insert_custom_collections( $data->custom_collections );

        /*

        Once this point is reached, all the data has been synced.
        set_transident allows for /products and /collections permalinks to work

        TODO: Make this more clear from within JS side
        TODO: Modularize

        */
        set_transient('wps_settings_updated', true);
        set_transient('wps_recently_connected', true);

        Transients::check_rewrite_rules();
        Transients::delete_cached_connections();

        wp_send_json_success($results);

      } else {
        wp_send_json_error($data->errors);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1038c)');

    }

	}


  /*

  TODO: NEED TESTING BELOW THIS FUNCTION

  Get Collections

  */
  public function wps_insert_smart_collections_data() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1037a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1037b)');

    try {

      $DB_Images = new Images();
      $DB_Collections_Smart = new Collections_Smart();

      $url = "https://" . $this->connection->domain . "/admin/smart_collections.json";

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $data = json_decode($guzzelResponse->getBody()->getContents());

      if (property_exists($data, "smart_collections")) {

        $results = $DB_Collections_Smart->insert_smart_collections($data->smart_collections);

        wp_send_json_success($results);

      } else {
        wp_send_json_error($data->errors);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1037c)');

    }

	}


  /*

  Get products from collection

  */
  public function wps_ws_get_products_from_collection() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1036a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1036b)');

    try {

      $collectionID = $_POST['collectionID'];

      $url = "https://" . $this->connection->domain . "/admin/products.json?collection_id=" . $collectionID;

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $productsResponse = json_decode($guzzelResponse->getBody()->getContents());

      if (property_exists($productsResponse, 'products')) {
        wp_send_json_success($productsResponse->products);

      } else {
        wp_send_json_error($productsResponse->errors);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1036c)');

    }

  }


  /*

  Get a list of collects by product ID

  */
  public function wps_insert_collects() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1035a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1035b)');

    try {

      $DB_Collects = new Collects();

      if(!isset($_POST['currentPage']) || !$_POST['currentPage']) {
        $currentPage = 1;

      } else {
        $currentPage = $_POST['currentPage'];
      }

      $url = "https://" . $this->connection->domain . "/admin/collects.json?limit=250&page=" . $currentPage;

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $data = json_decode($guzzelResponse->getBody()->getContents());

      if (property_exists($data, "collects")) {

        $resultProducts = $DB_Collects->insert_collects($data->collects);
        wp_send_json_success($data->collects);

      } else {

        wp_send_json_error($data->errors);

      }


    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1035c)');

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


    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1034a)');

    if ($ajax) {
      Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1034b)');
    }


    try {

      $url = "https://" . $this->connection->domain . "/admin/collects.json?product_id=" . $productID;

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $data = json_decode($guzzelResponse->getBody()->getContents());

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

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1034c)');

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

    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1033a)');

    if ($ajax) {
      Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1033b)');
    }


    try {

      $url = "https://" . $this->connection->domain . "/admin/collects.json?collection_id=" . $collectionID;

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $data = json_decode($guzzelResponse->getBody()->getContents());

      if (property_exists($data, 'collects')) {
        $response = $data->collects;

      } else {
        $response = $data->errors;

      }

    } catch (RequestException $error) {

      $response = $this->wps_get_error_message($error) . ' (Error code: #1033c)';

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

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1032a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1032b)');

    try {

      $collectionID = $_POST['collectionID'];

      $url = "https://" . $this->connection->domain . "/admin/custom_collections/" . $collectionID . ".json";

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $data = json_decode($guzzelResponse->getBody()->getContents());

      if (property_exists($data, 'custom_collection')) {
        wp_send_json_success($data);

      } else {
        wp_send_json_error($data->errors);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1032c)');

    }

  }


  /*

  Invalidate the Shopify API connection

  */
  public function wps_ws_end_api_connection() {

    if (Utils::emptyConnection($this->connection)) {
      return new \WP_Error('error', $this->messages->message_connection_disconnect_invalid_access_token . ' (Error code: #1031a)');

    } else {

      try {

        $url = "https://" . $this->connection->domain . "/admin/api_permissions/current.json";

        $headers = array(
          "Content-Type" => "application/json",
          "Accept" => "application/json",
          "Content-Length" => "0",
          "X-Shopify-Access-Token" => $this->connection->access_token
        );

        $Guzzle = new Guzzle();
        $guzzelResponse = $Guzzle->delete($url, array(
          'headers' => $headers,
          'on_headers' => function(ResponseInterface $response) {
            $this->wps_ws_check_rate_limit($response);
          }
        ));

        return true;

      } catch (RequestException $error) {

        return new \WP_Error('error', $this->wps_get_error_message($error) . ' (Error code: #1031b)');

      }

    }

  }


  /*

  When the user is sent back to their site ...

  TODO: More effective way to do this?

  At this point we know that all the validation and authorization checks
  have passed (because auth=true). We can now get our Shopify access token.
  However before we can do that, we need to collect all the values that we'll
  need for the call. Those values are ...

    1. code
    2. api key
    3. shared secret
    4. shop

  We also need to verify that the shop domain that is passed into the URL
  parameters has a corrosponding code (generated via shopify). We can access
  this data from the WP Shopify database. To find the code, we'll do a lookup
  by shop domain AND nonce.

  */
  public function wps_ws_on_authorization() {

    if (Utils::backFromShopify()) {

      $WPS_Waypoint = new Waypoints($this->config);
      $WPS_Webhooks = new Webhooks($this->config);

      $waypointSettings = json_decode( $WPS_Waypoint->wps_waypoint_settings() );

      $waypointClients = $WPS_Waypoint->wps_waypoint_clients( $WPS_Waypoint->wps_waypoint_auth() );

      $matchedWaypointClient = $WPS_Waypoint->wps_waypoint_filter_clients($waypointClients);

      $accessTokenData = Utils::wps_construct_access_token_data($matchedWaypointClient, $waypointSettings);


      // Get Shopify Access Token
      $token = $WPS_Waypoint->wps_waypoint_get_access_token($accessTokenData);

      // Save Shopify Access Token
      $WPS_Waypoint->wps_waypoint_save_access_token($token);

      // Registers all webhooks.
      $WPS_Webhooks->wps_webhooks_register();

    }

  }


  /*

	Get Webhooks

	*/
	public function wps_ws_get_webhooks() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1030a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1030b)');

    try {

      $url = "https://" . $this->connection->domain . "/admin/webhooks.json";

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $data = $guzzelResponse->getBody()->getContents();

      wp_send_json_success($data);


    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1030c)');

    }

	}


  /*

  Delete Webhooks
  TODO: Are we actually deleting? Do we actually need?

  */
  public function wps_ws_delete_webhook() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1029a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1029b)');


    if (property_exists($this->connection, 'webhook_id') && $this->connection->webhook_id) {

      try {

        $url = "https://" . $this->connection->domain . "/admin/webhooks/" . $this->connection->webhook_id . ".json";

        $headers = array(
          'X-Shopify-Access-Token' => $this->connection->access_token
        );

        $Guzzle = new Guzzle();
        $guzzelResponse = $Guzzle->request('GET', $url, array(
          'headers' => $headers
        ));

        $data = $guzzelResponse->getBody()->getContents();
        wp_send_json_success($data);

      } catch (RequestException $error) {

        wp_send_json_error( $this->wps_get_error_message($error) . ' (Error code: #1029c)');

      }

    } else {

      return new \WP_Error('error', $this->messages->message_webhooks_no_id_set . ' (Error code: #1029d)');

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

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #103a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #103b)');

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

    Utils::valid_backend_nonce($_GET['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #104a)');

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

  Insert connection data

  */
  public function wps_insert_connection() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #105a)');

    $DB_Settings_Connection = new Settings_Connection();
    $connectionData = $_POST['connectionData'];
    $connectionData = (array) $connectionData;

    $results = $DB_Settings_Connection->insert_connection($connectionData);

    if ($results === false) {
      wp_send_json_error($this->messages->message_connection_save_error . ' (Error code: #105b)');

    } else {
      wp_send_json_success($results);
    }

  }


  /*

  Insert Shop Data

  */
  public function wps_insert_shop() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #106a)');

    $DB_Shop = new Shop();
    $shopData = $_POST['shopData'];
    $results = $DB_Shop->insert_shop($shopData);

    wp_send_json_success($results);

  }


  /*

  Delete Shop Data

  */
  public function wps_delete_shop() {

    $DB_Shop = new Shop();

    if (!$DB_Shop->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_shop_error . ' (Error code: #107a)');

    } else {
      return true;
    }

  }


  /*

	Delete the config data
  TODO: Support multiple connections by making connection ID dynamic

	*/
	public function wps_delete_settings_connection() {

		$DB_Settings_Connection = new Settings_Connection();

    $deeltion = $DB_Settings_Connection->delete();

    if (!$deeltion) {
      return new \WP_Error('error', $this->messages->message_delete_connection_error . ' (Error code: #108a)');

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
      $result = new \WP_Error('error', $this->messages->message_delete_cpt_products_error . ' (Error code: #109a)');
    }

    if (!$Backend->wps_delete_posts('wps_collections')) {
      $result = new \WP_Error('error', $this->messages->message_delete_cpt_collections_error . ' (Error code: #109b)');
    }

    return $result;

  }


  /*

  wps_delete_images

  */
  public function wps_delete_images() {

    $Images = new Images();

    if (!$Images->delete()) {
      return new \WP_Error('error', $this->messages->message_delete_product_images_error . ' (Error code: #1010a)');

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
      return new \WP_Error('error', $this->messages->message_delete_product_inventory_error . ' (Error code: #1011a)');

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
      return new \WP_Error('error', $this->messages->message_delete_collects_error . ' (Error code: #1012a)');

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
      return new \WP_Error('error', $this->messages->message_delete_product_tags_error . ' (Error code: #1013a)');

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
      return new \WP_Error('error', $this->messages->message_delete_product_options_error . ' (Error code: #1014a)');

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
      return new \WP_Error('error', $this->messages->message_delete_product_variants_error . ' (Error code: #1015a)');

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
      return new \WP_Error('error', $this->messages->message_delete_products_error . ' (Error code: #1016a)');

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
      return new \WP_Error('error', $this->messages->message_delete_custom_collections_error . ' (Error code: #1017a)');

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
      return new \WP_Error('error', $this->messages->message_delete_smart_collections_error . ' (Error code: #1017a)');

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
      return new \WP_Error('error', $this->messages->message_delete_orders_error . ' (Error code: #1018a)');

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
      return new \WP_Error('error', $this->messages->message_delete_customers_error . ' (Error code: #1019a)');

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

  1. Delete all the synced products and collections data
  2. Invalidate the main Shopify API connection:
  3. Remove the wps config values from the database
  4. Delete cache


  TODO: Since invalidating the main Shopify API connection is
  performed asynchronously, we should break that into its own
  request; perhaps after this one.

  Each deletion returns either type boolean of TRUE or a type
  STRING containing the error message.

  */
  public function wps_uninstall_consumer($ajax = true) {

    if ($_POST['action'] === 'wps_uninstall_consumer') {
      $ajax = true;

    } else {
      $ajax = false;
    }

    if ($ajax) {
      Utils::valid_uninstall_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1020a)');
    }

    $results = array();
    $Transients = new Transients();
    $DB_Settings_Connection = new Settings_Connection();
    $connection = $DB_Settings_Connection->get_column_single('domain');
    $results = $this->wps_uninstall_product_data();


    if (!empty($connection)) {

      /*

      Remove API Connection

      */
      $response_connection_api = $this->wps_ws_end_api_connection();

      if (is_wp_error($response_connection_api)) {
        $results['connection_api'] = $response_connection_api->get_error_message()  . ' (Error code: #1020b)';

      } else {
        $results['connection_api'] = $response_connection_api;
      }


      /*

      Remove Connection Settings

      */
      $response_connection_settings = $this->wps_delete_settings_connection();

      if (is_wp_error($response_connection_settings)) {
        $results['connection_settings'] = $response_connection_settings->get_error_message()  . ' (Error code: #1020c)';

      } else {
        $results['connection_settings'] = $response_connection_settings;
      }

    }


    if ($ajax) {
      wp_send_json_success($results);

    } else {
      return $results;

    }

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
      Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid  . ' (Error code: #1021a)');
    }


    $results = array();
    $Transients = new Transients();
    $DB_Settings_Connection = new Settings_Connection();
    $connection = $DB_Settings_Connection->get_column_single('domain');


    /*

    Remove Shop

    */
    $response_shop = $this->wps_delete_shop();

    if (is_wp_error($response_shop)) {
      $results['shop'] = $response_shop->get_error_message()  . ' (Error code: #1021b)';

    } else {
      $results['shop'] = $response_shop;
    }


    /*

    Remove CPTs

    */
    // $response_cpt = $this->wps_delete_synced_data();
    //
    // if (is_wp_error($response_cpt)) {
    //   $results['cpt'] = $response_cpt->get_error_message();
    //
    // } else {
    //   $results['cpt'] = $response_cpt;
    // }

    /*

    Remove Products

    */
    $response_products = $this->wps_delete_products();

    if (is_wp_error($response_products)) {
      $results['products'] = $response_products->get_error_message()  . ' (Error code: #1021c)';

    } else {
      $results['products'] = $response_products;
    }


    /*

    Remove Custom Collections

    */
    $response_collections_custom = $this->wps_delete_custom_collections();

    if (is_wp_error($response_collections_custom)) {
      $results['custom_collections'] = $response_collections_custom->get_error_message()  . ' (Error code: #1021d)';

    } else {
      $results['custom_collections'] = $response_collections_custom;
    }


    /*

    Remove Smart Collections

    */
    $response_collections_smart = $this->wps_delete_smart_collections();

    if (is_wp_error($response_collections_smart)) {
      $results['smart_collections'] = $response_collections_smart->get_error_message()  . ' (Error code: #1021e)';

    } else {
      $results['smart_collections'] = $response_collections_smart;
    }


    /*

    Remove Collects

    */
    $response_collects = $this->wps_delete_collects();

    if (is_wp_error($response_collects)) {
      $results['collects'] = $response_collects->get_error_message()  . ' (Error code: #1021f)';

    } else {
      $results['collects'] = $response_collects;
    }


    /*

    Remove Variants

    */
    $response_variants = $this->wps_delete_variants();

    if (is_wp_error($response_variants)) {
      $results['variants'] = $response_variants->get_error_message()  . ' (Error code: #1021g)';

    } else {
      $results['variants'] = $response_variants;
    }


    /*

    Remove Options

    */
    $response_options = $this->wps_delete_options();

    if (is_wp_error($response_options)) {
      $results['options'] = $response_options->get_error_message()  . ' (Error code: #1021h)';

    } else {
      $results['options'] = $response_options;
    }


    /*

    Remove Tags

    */
    $response_tags = $this->wps_delete_tags();

    if (is_wp_error($response_tags)) {
      $results['tags'] = $response_tags->get_error_message()  . ' (Error code: #1021i)';

    } else {
      $results['tags'] = $response_tags;
    }


    /*

    Remove Images

    */
    $response_images = $this->wps_delete_images();

    if (is_wp_error($response_images)) {
      $results['images'] = $response_images->get_error_message()  . ' (Error code: #1021j)';

    } else {
      $results['images'] = $response_images;
    }


    /*

    Remove Orders

    */
    $response_orders = $this->wps_delete_orders();

    if (is_wp_error($response_orders)) {
      $results['orders'] = $response_orders->get_error_message()  . ' (Error code: #1021k)';

    } else {
      $results['orders'] = $response_orders;
    }


    /*

    Remove Customers

    */
    $response_customers = $this->wps_delete_customers();

    if (is_wp_error($response_customers)) {
      $results['customers'] = $response_customers->get_error_message()  . ' (Error code: #1021l)';

    } else {
      $results['customers'] = $response_customers;
    }


    /*

    Remove Transients

    */
    $response_transients = $Transients->delete_all_cache();

    if (is_wp_error($response_transients)) {
      $results['transients'] = $response_transients->get_error_message()  . ' (Error code: #1021m)';

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

  Set syncing indicator

  */
  public function wps_ws_set_syncing_indicator($ajax = true) {

    $DB_Settings_Connection = new Settings_Connection();
    $connectionData = $DB_Settings_Connection->get();

    if ($_POST['action'] === 'wps_ws_set_syncing_indicator') {
      $ajax = true;

    } else {
      $ajax = false;
    }


    if ($ajax) {
      Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid  . ' (Error code: #1022a)');
    }


    $connectionData->is_syncing = $_POST['syncing'];
    $connectionData = (array) $connectionData;

    $response = $DB_Settings_Connection->insert_connection($connectionData);

    if (is_wp_error($response)) {
      $results['syncing_indicator'] = $response->get_error_message()  . ' (Error code: #1022b)';

    } else {
      $results['syncing_indicator'] = $response;
    }


    if ($ajax) {
      wp_send_json_success($results);

    } else {
      return $results;

    }

  }


  /*

  Clear Cache

  */
  public function wps_clear_cache() {

    Utils::valid_cache_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid  . ' (Error code: #1023a)');

    $Transients = new Transients();
    $results = $Transients->delete_all_cache();

    if (is_wp_error($results)) {
      wp_send_json_error(esc_html__($results->get_error_message()  . ' (Error code: #1023b)', 'wp-shopify'));

    } else {
      wp_send_json_success($results);
    }

  }


  /*

  Sync with CPT

  CURRENTLY NOT USED

  */
  public function wps_sync_with_cpt() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid  . ' (Error code: #1024a)');

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

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid  . ' (Error code: #1025a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found  . ' (Error code: #1025b)');

    try {

      $Utils = new Utils();
      $DB_Orders = new Orders();

      if (!isset($_POST['currentPage']) || !$_POST['currentPage']) {
        $currentPage = 1;

      } else {
        $currentPage = $_POST['currentPage'];
      }

      $url = "https://" . $this->connection->domain . "/admin/orders.json?status=any&page=" . $currentPage;

      /*

      If Access Token is expired or wrong the follow error will result:
      "[API] Invalid API key or access token (unrecognized login or wrong password)"

      */
      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $data = json_decode($guzzelResponse->getBody()->getContents());

      if (is_object($data) && property_exists($data, 'orders')) {

        /*

        This is where the bulk of product data is inserted into the database. The
        "insert_products" method inserts both the CPT's and custom WPS table data.

        */
        $resultOrders = $DB_Orders->insert_orders( $data->orders );

        if (empty($resultOrders)) {
          wp_send_json_error($this->messages->message_syncing_orders_error  . ' (Error code: #1025c)');
        }

        $insertionResults['orders'] = $resultOrders;

        wp_send_json_success($insertionResults);

      } else {
        wp_send_json_error($data->errors);

      }

    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error)  . ' (Error code: #1025d)');

    }

  }


  /*

  Insert Customers

  */
  public function wps_insert_customers() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid  . ' (Error code: #1026a)');
    !Utils::emptyConnection($this->connection) ?: wp_send_json_error($this->messages->message_connection_not_found  . ' (Error code: #1026b)');

    try {

      $Utils = new Utils();
      $DB_Customers = new Customers();

      if (!isset($_POST['currentPage']) || !$_POST['currentPage']) {
        $currentPage = 1;

      } else {
        $currentPage = $_POST['currentPage'];
      }

      $url = "https://" . $this->connection->domain . "/admin/customers.json?page=" . $currentPage;

      /*

      If Access Token is expired or wrong the follow error will result:
      "[API] Invalid API key or access token (unrecognized login or wrong password)"

      */
      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers,
        'on_headers' => function(ResponseInterface $response) {
          $this->wps_ws_check_rate_limit($response);
        }
      ));

      $data = json_decode($guzzelResponse->getBody()->getContents());

      if (is_object($data) && property_exists($data, 'customers')) {

        /*

        This is where the bulk of product data is inserted into the database. The
        "insert_products" method inserts both the CPT's and custom WPS table data.

        */
        $results = $DB_Customers->insert_customers( $data->customers );

        if (empty($results)) {
          wp_send_json_error($this->messages->message_syncing_customers_error  . ' (Error code: #1026c)');
        }

        $insertionResults['customers'] = $results;

        wp_send_json_success($insertionResults);

      } else {
        wp_send_json_error($data->errors);

      }


    } catch (RequestException $error) {

      wp_send_json_error( $this->wps_get_error_message($error)  . ' (Error code: #1026d)');

    }

  }


  /*

  Heartbeat: Receive

  */
  public function wps_receive_heartbeat( $response, $data ) {

    // If we didn't receive our data, don't send any back.
    if ( empty( $data['myplugin_customfield'] ) ) {
      return $response;
    }

    error_log('---- $response -----');
    error_log(print_r($response, true));
    error_log('---- /$response -----');

    error_log('---- $data -----');
    error_log(print_r($data, true));
    error_log('---- /$data -----');

    // Calculate our data and pass it back. For this example, we'll hash it.
    $received_data = $data['myplugin_customfield'];

    $response['myplugin_customfield_hashed'] = sha1( $received_data );

    return $response;

  }


}
