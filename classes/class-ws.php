<?php

namespace WPS;

use WPS\Backend;
use WPS\DB\Settings_Connection;
use WPS\DB\Settings_General;
use WPS\DB\Settings_License;
use WPS\DB\Shop;
use WPS\DB\Products;
use WPS\DB\Variants;
use WPS\DB\Collects;
use WPS\Transients;
use WPS\DB\Options;
use WPS\DB\Collections_Custom;
use WPS\DB\Collections_Smart;
use WPS\DB\Images;
use WPS\DB\Tags;

/*

Class Web Service

*/
class WS {

  protected static $instantiated = null;

  private $Config;

	/*

	Initialize the class and set its properties.

	*/
	public function __construct($Config) {
		$this->config = $Config;
    $this->connection = $this->config->wps_get_settings_connection();
    $this->connection_option_name = $this->config->settings_connection_option_name;

    $this->general = $this->config->wps_get_settings_general();
    $this->general_option_name = $this->config->settings_general_option_name;
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

  Get Products Count

  */
  public function wps_ws_get_products_count() {

    $url = "https://" . $this->connection->domain . "/admin/products/count.json";

    $headers = array(
      'X-Shopify-Access-Token' => $this->connection->access_token
    );

    $response = \Requests::get($url, $headers);
    $data = json_decode($response->body);

    if (property_exists($data, 'count')) {
      echo json_encode($data);
      die();

    } else {
      echo json_encode($data->errors);
      die();

    }

  }


  /*

  Get Products

  */
  public function wps_ws_get_collects_count() {

    $url = "https://" . $this->connection->domain . "/admin/collects/count.json";

    $headers = array(
      'X-Shopify-Access-Token' => $this->connection->access_token
    );

    $response = \Requests::get($url, $headers);

    $data = json_decode($response->body);

    if (property_exists($data, 'count')) {
      echo json_encode($data);
      die();

    } else {
      echo json_encode($data->errors);
      die();

    }

  }





























  /*

  Get Shop Data

  */
  public function wps_ws_get_shop_data() {

    $url = "https://" . $this->connection->domain . "/admin/shop.json";

    $headers = array(
      'X-Shopify-Access-Token' => $this->connection->access_token
    );

    $response = \Requests::get($url, $headers);
    $data = json_decode($response->body);

    if (property_exists($data, 'shop')) {
      echo json_encode($data);
      die();

    } else {
      echo json_encode($data->errors);
      die();

    }

  }









  /*

  Get Products + Variants

  Here we make our requests to the API to insert products and variants

  */
  public function wps_insert_products_data() {

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

    $response = \Requests::get($url, $headers);
    $data = json_decode($response->body);


    // $data = false;
    if (property_exists($data, "products")) {

      /*

      This is where the bulk of product data is inserted into the database. The
      "insert_products" method inserts both the CPT's and custom WPS table data.

      */
      $resultProducts = $DB_Products->insert_products( $data->products );
      $resultVariants = $DB_Variants->insert_variants( $data->products );
      $resultOptions = $DB_Options->insert_options( $data->products );
      $resultImages = $DB_Images->insert_images( $data->products );

      echo json_encode($data->products);
      die();

    } else {

      echo json_encode($data->errors);
      die();

    }

  }


  /*

  Get Variants

  */
  public function wps_ws_get_variants() {

    $productID = $_POST['productID'];

    if(!isset($_POST['currentPage']) || !$_POST['currentPage']) {
      $currentPage = 1;

    } else {
      $currentPage = $_POST['currentPage'];
    }

    if (empty($productID)) {
      return false;

    } else {

      $url = "https://" . $this->connection->domain . "/admin/products/" . $productID . "/variants.json?limit=250&page=" . $currentPage;

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $response = \Requests::get($url, $headers);
      $data = json_decode($response->body);

      if (property_exists($data, 'variants')) {
        echo json_encode($data);
        die();

      } else {
        echo json_encode($data->errors);
        die();

      }

    }

  }


  /*

  Get Collections

  */
  public function wps_insert_custom_collections_data() {

    $DB_Collections_Custom = new Collections_Custom();

		$url = "https://" . $this->connection->domain . "/admin/custom_collections.json";

		$headers = array(
			'X-Shopify-Access-Token' => $this->connection->access_token
		);

		$response = \Requests::get($url, $headers);
    $data = json_decode($response->body);

    if (property_exists($data, "custom_collections")) {
      $results = $DB_Collections_Custom->insert_custom_collections( $data->custom_collections );

      Transients::check_rewrite_rules();

      echo json_encode($results);
  		die();

    } else {

      echo json_encode($data->errors);
      die();

    }


	}


  /*

  Get Collections

  */
  public function wps_insert_smart_collections_data() {

    $DB_Collections_Smart = new Collections_Smart();

		$url = "https://" . $this->connection->domain . "/admin/smart_collections.json";

		$headers = array(
			'X-Shopify-Access-Token' => $this->connection->access_token
		);

		$response = \Requests::get($url, $headers);
    $data = json_decode($response->body);


    if (property_exists($data, "smart_collections")) {
      $results = $DB_Collections_Smart->insert_smart_collections( $data->smart_collections );

      echo json_encode($results);
  		die();

    } else {

      echo json_encode($data->errors);
      die();

    }


	}


  /*

  Get products from collection

  */
  public function wps_ws_get_products_from_collection() {

    $collectionID = $_POST['collectionID'];

    $url = "https://" . $this->connection->domain . "/admin/products.json?collection_id=" . $collectionID;

    $headers = array(
      'X-Shopify-Access-Token' => $this->connection->access_token
    );

    $response = \Requests::get($url, $headers);
    $data = json_decode($response->body);

    if (property_exists($data, 'products')) {
      echo json_encode($data);
      die();

    } else {
      echo json_encode($data->errors);
      die();

    }

  }


  /*

  Get a list of collects by product ID

  */
  public function wps_insert_collects() {

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

    $response = \Requests::get($url, $headers);
    $data = json_decode($response->body);

    if (property_exists($data, "collects")) {

      $resultProducts = $DB_Collects->insert_collects( $data->collects );
      echo json_encode($data->collects);
      die();

    } else {

      echo json_encode($data->errors);
      die();

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

		$url = "https://" . $this->connection->domain . "/admin/collects.json?product_id=" . $productID;

		$headers = array(
			'X-Shopify-Access-Token' => $this->connection->access_token
		);

    $response = \Requests::get($url, $headers);
    $data = json_decode($response->body);

    if (property_exists($data, 'collects')) {

      if ($ajax) {
        echo json_encode($data);
        die();

      } else {
        return $data;

      }

    } else {
      echo json_encode($data->errors);
      die();

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

    $url = "https://" . $this->connection->domain . "/admin/collects.json?collection_id=" . $collectionID;

    $headers = array(
      'X-Shopify-Access-Token' => $this->connection->access_token
    );

    $response = \Requests::get($url, $headers);
    $data = json_decode($response->body);

    if (property_exists($data, 'collects')) {

      if ($ajax) {
        echo json_encode($data);
        die();

      } else {
        return $data;

      }

    } else {
      echo json_encode($data->errors);
      die();

    }

  }


  /*

  Get single collection

  */
  public function wps_ws_get_single_collection() {

    $collectionID = $_POST['collectionID'];

    $url = "https://" . $this->connection->domain . "/admin/custom_collections/" . $collectionID . ".json";

    $headers = array(
      'X-Shopify-Access-Token' => $this->connection->access_token
    );

    $response = \Requests::get($url, $headers);
    $data = json_decode($response->body);

    if (property_exists($data, 'custom_collection')) {
      echo json_encode($data);
      die();

    } else {
      echo json_encode($data->errors);
      die();

    }

  }


  /*

  Invalidate the Shopify API connection

  */
  public function wps_ws_end_api_connection() {

    if(isset($this->connection->access_token) && $this->connection->access_token) {

      $url = "https://" . $this->connection->domain . "/admin/api_permissions/current.json";

      $headers = array(
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'Content-Length' => '0',
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $response = \Requests::delete($url, $headers);

    } else {
      $response = 'No Access token set';

    }

    return $response;

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

    if(isset($_GET["auth"]) && trim($_GET["auth"]) == 'true') {

      $WPS_Waypoint = new Waypoints($this->config);
      $WPS_Webhooks = new Webhooks($this->config);

      $waypointSettings = json_decode( $WPS_Waypoint->wps_waypoint_settings() );

      $waypointClients = $WPS_Waypoint->wps_waypoint_clients( $WPS_Waypoint->wps_waypoint_auth() );

      $matchedWaypointClient = $WPS_Waypoint->wps_waypoint_filter_clients($waypointClients);

      $accessTokenData = array(
        'client_id' => $waypointSettings->wps_api_key,
        'client_secret' => $waypointSettings->wps_shared_secret,
        'code' => $matchedWaypointClient->code,
        'shop' => $matchedWaypointClient->shop
      );

      // Save Shopify Access Token
      $WPS_Waypoint->wps_waypoint_save_access_token($WPS_Waypoint->wps_waypoint_get_access_token($accessTokenData));

      // Registers all webhooks.
      $WPS_Webhooks->wps_webhooks_register();

    }

  }


  /*

	Get Webhooks

	*/
	public function wps_ws_get_webhooks() {

		if (isset($this->connection->domain) && $this->connection->domain) {
			$url = "https://" . $this->connection->domain . "/admin/webhooks.json";

			$headers = array(
				'X-Shopify-Access-Token' => $this->connection->access_token
			);

			$response = \Requests::get($url, $headers);
			$data = $response->body;

		} else {
			$data = 'Domain not set';

		}

		echo $data;
		exit();

	}


  /*

  Delete Webhooks

  */
  public function wps_ws_delete_webhook() {

    if (isset($this->connection->webhook_id) && $this->connection->webhook_id) {

      $url = "https://" . $this->connection->domain . "/admin/webhooks/" . $this->connection->webhook_id . ".json";

      $headers = array(
        'X-Shopify-Access-Token' => $this->connection->access_token
      );

      $response = \Requests::get($url, $headers);
			$data = $response->body;

    } else {
      $data = "No Webhook ID set";

    }

    // Should equal our config settings minus webhook id only
    // $respWP = $this->wps_delete_setting('webhook_id');

    // update_option( $this->plugin_name, $resp );

    exit();

  }


  function wps_get_progress_count() {

    echo json_encode($_SESSION);
    die();

  }




  public function wps_update_settings_general() {

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

    $results = $DB_Settings_General->update_general($newGeneralSettings);

    set_transient('wps_settings_updated', $newGeneralSettings);

    echo json_encode($results);
    die();

  }


  /*

  Reset rewrite rules on CTP url change

  */
  public function wps_reset_rewrite_rules($old_value, $new_value) {
    update_option('rewrite_rules', '');
  }















/*




NEW STRUCTURE






*/













  /*

  Reset rewrite rules on CTP url change

  */
  public function wps_get_connection() {

    $DB_Settings_Connection = new Settings_Connection();
    $connectionData = $DB_Settings_Connection->get();

    set_transient('wps_settings_updated', $DB_Settings_Connection);

    echo json_encode($connectionData);
    die();

  }


  /*

  Insert connection data

  */
  public function wps_insert_connection() {

    $DB_Settings_Connection = new Settings_Connection();
    $connectionData = $_POST['connectionData'];

    $results = $DB_Settings_Connection->insert_connection($connectionData);

    echo json_encode($results);
    die();

  }


  /*

  Insert Shop Data

  */
  public function wps_insert_shop() {

    $DB_Shop = new Shop();
    $shopData = $_POST['shopData'];

    $results = $DB_Shop->insert_shop($shopData);

    echo json_encode($results);
    die();

  }





  /*

  Delete Shop Data

  */
  public function wps_delete_shop() {

    $DB_Shop = new Shop();

    return $DB_Shop->delete();

    // $DB_Settings_Connection = new Settings_Connection();
    //
    // // Get the currently active domain
    // $connection = $DB_Settings_Connection->get_column_single('domain');
    //
    // if (!empty($connection)) {
    //
    //   // Get the domain prefix
    //   $domainPrefix = Utils::wps_get_domain_prefix($connection[0]->domain);
    //
    //   // Get the currently active Shop by ID by domain
    //   $activeShopID = $DB_Shop->get_column_by('id', 'name', $domainPrefix);
    //
    //   // Perform the actual deletion
    //   $results = $DB_Shop->delete($activeShopID);
    //
    // } else {
    //   $results = array();
    //
    // }

  }













  /*

	Delete the config data

	*/
	public function wps_delete_settings_connection() {

		$DB_Settings_Connection = new Settings_Connection();

		// Delete any connected webhooks
		// $this->wps_del_webhook();
		// TODO: Delete webhook connections
		// TODO: Delete License key info


    //
    // We can safely hardcode 1 for now since our plugin
    // only supports single connections
    // TODO: Support multiple connections by making connection ID dynamic
    //
		$results = $DB_Settings_Connection->delete();


		// $this->wps_delete_connection_setting('js_access_token');
		// $this->wps_delete_connection_setting('domain');
		// $this->wps_delete_connection_setting('nonce');
		// $this->wps_delete_connection_setting('app_id');
		// $this->wps_delete_connection_setting('webhook_id');
		// $this->wps_delete_connection_setting('access_token');

    return $results;

	}


  /*

  Delete the synced Shopify data

  */
  public function wps_delete_synced_data() {

    $Backend = new Backend($this->config);

    $Backend->wps_delete_posts('wps_products');
    $Backend->wps_delete_posts('wps_collections');

  }



  /*

  wps_delete_images

  */
  public function wps_delete_images() {

    $Images = new Images();
    $Images->delete();

  }


  /*

  wps_delete_images

  */
  public function wps_delete_inventory() {

    $Inventory = new Inventory();
    $Inventory->delete();

  }



  /*

  wps_delete_collects

  */
  public function wps_delete_collects() {

    $Collects = new Collects();
    $Collects->delete();

  }


  /*

  wps_delete_options

  */
  public function wps_delete_tags() {

    $Tags = new Tags();
    $Tags->delete();

  }


  /*

  wps_delete_options

  */
  public function wps_delete_options() {

    $Options = new Options();
    $Options->delete();

  }


  /*

  wps_delete_variants

  */
  public function wps_delete_variants() {

    $Variants = new Variants();
    $Variants->delete();

  }


  /*

  wps_delete_products

  */
  public function wps_delete_products() {

    $Products = new Products();
    $Products->delete();

  }


  /*

  wps_delete_products

  */
  public function wps_delete_custom_collections() {

    $Collections_Custom = new Collections_Custom();
    $Collections_Custom->delete();

  }


  /*

  wps_delete_products

  */
  public function wps_delete_smart_collections() {

    $Collections_Smart = new Collections_Smart();
    $Collections_Smart->delete();

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

  */
  public function wps_uninstall_consumer($ajax = true) {

    /*

    Need to do a few things here ...

    1. Delete all the synced products and collections data
    2. Invalidate the main Shopify API connection:
    3. Remove the wps config values from the database


    TODO: Since invalidating the main Shopify API connection is
    performed asynchronous, we should break that into its own request.
    Perhaps after this one.

    */

    $results = array();
    $DB_Settings_Connection = new Settings_Connection();
    $connection = $DB_Settings_Connection->get_column_single('domain');

    if (!empty($connection)) {

      $results[] = $this->wps_ws_end_api_connection();
      $results[] = $this->wps_delete_shop();
      $results[] = $this->wps_delete_settings_connection();
      $results[] = $this->wps_delete_synced_data();

      $results['products'] = $this->wps_delete_products();
      $results['collects'] = $this->wps_delete_collects();
      $results['variants'] = $this->wps_delete_variants();
      $results['options'] = $this->wps_delete_options();
      $results['tags'] = $this->wps_delete_tags();

      $results['custom_collections'] = $this->wps_delete_custom_collections();
      $results['smart_collections'] = $this->wps_delete_smart_collections();

      $results['images'] = $this->wps_delete_images();
      // $results['inventory'] = $this->wps_delete_inventory();

    }

    if ($ajax) {
      echo json_encode($results);
      die();

    } else {
      return $results;
    }



  }



}
