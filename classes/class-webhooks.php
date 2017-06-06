<?php

namespace WPS;

use WPS\Utils;
// require_once plugin_dir_path( __FILE__ ) . '../admin/class-admin.php';

/*

Class Webhooks

*/
class Webhooks {

  protected static $instantiated = null;
  private $Config;

	/*

	Initialize the class and set its properties.

	*/
	public function __construct($Config) {
		$this->config = $Config;
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

  Saving webhook plugin settings
  TODO: Same as function above, combine into utility

  */
  public function wps_webhooks_save_id($webhookID) {

    $connection = $this->config->wps_get_settings_connection();
    $connection->webhook_id = $webhookID;

    // TODO: Check that the update worked before returning
    update_option($this->config->settings_connection_option_name, $connection);

    return $webhookID;

  }


  /*

  Webhook: collections/create

  */
  public function wps_webhooks_collections_create() {
    include($this->config->plugin_path . 'webhooks/collections/collections-create.php');
  }


  /*

  Webhook: collections/update

  */
  public function wps_webhooks_collections_update() {
    include($this->config->plugin_path . 'webhooks/collections/collections-update.php');
  }


  /*

  Webhook: collections/delete

  */
  public function wps_webhooks_collections_delete() {
    include($this->config->plugin_path . 'webhooks/collections/collections-delete.php');
  }


  /*

  Webhook: products/delete

  */
  public function wps_webhooks_product_create() {
    include($this->config->plugin_path . 'webhooks/products/product-create.php');
  }


  /*

  Webhook: products/update

  */
  public function wps_webhooks_product_update() {
    include($this->config->plugin_path . 'webhooks/products/product-update.php');
  }


  /*

  Webhook: products/delete

  */
  public function wps_webhooks_product_delete() {
    include($this->config->plugin_path . 'webhooks/products/product-delete.php');
  }


  /*

  Webhook: shop/update

  */
  public function wps_webhooks_shop_update() {
    include($this->config->plugin_path . 'webhooks/shop/shop-update.php');
  }


  /*

  Webhook: app/uninstalled

  */
  public function wps_webhooks_shop_app_uninstalled() {
    include($this->config->plugin_path . 'webhooks/app/app-uninstalled.php');
  }


  /*

  Register Webhook

  Notes:

  1. TODO: Research SSL read error
  2. REVISIT: Only use HTTP version for 'address' within data

  */
  public function wps_webhooks_register_single($topic, $receiver) {

    $connection = $this->config->wps_get_settings_connection();
    $general = $this->config->wps_get_settings_general();

    // $webhookDomain = Utils::wps_get_webhooks_domain();

    if(isset($connection->access_token) && $connection->access_token) {

      // URL to usein our POST
      $url = "https://" . $connection->domain . "/admin/webhooks.json";

      // Headers to send to Shopify in our POST
      $headers = array(
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'X-Shopify-Access-Token' => $connection->access_token
      );

      /*

      This is the URI where Shopify will send its POST request when an event occurs.

      */
      $customWebbooksURL = $general->url_webhooks;
      $homeURL = get_home_url(); // also default webhook URL
      $adminURL = admin_url();



      if ($homeURL !== $customWebbooksURL) {

        error_log('Webhooks URL is different from Home URL');
        $adminPath = Utils::wps_construct_admin_path_from_urls($homeURL, $adminURL);
        error_log('Newly constructed');
        error_log(print_r($adminPath, true));

        $callbackURL = $customWebbooksURL . $adminPath . "admin-ajax.php?action=" . $receiver;
        error_log('Final callback URL');
        error_log(print_r($callbackURL, true));

      } else {
        error_log('Both URLs are the same');
        $callbackURL = admin_url('admin-ajax.php') . "?action=" . $receiver;
      }


      // error_log('::: Custom Webhooks URL :::');
      // error_log(print_r($customWebbooksURL, true));
      //
      // error_log('::: Admin URL for admin AJAX :::');
      // error_log(print_r(admin_url('admin-ajax.php'), true));
      //
      // error_log('::: Home URL :::');
      // error_log(print_r(admin_url(), true));
      //
      // error_log('::: Site URL :::');
      // error_log(print_r(site_url(), true));



      // Data to send to Shopify in our POST
      $data = array(
        "webhook" => array(
          "topic"     => $topic,
          "address"   => $callbackURL,
          "format"    => "json"
        )
      );

      $resp = \Requests::post($url, $headers, json_encode($data));
      $resp = json_decode($resp->body, true);

      // Saving webhook ID into database
      if(isset($resp['webhook']) && $resp['webhook']) {

        $webhookID = (string)$resp['webhook']['id'];
        return $webhookID;
        // $webhookID = $this->wps_webhooks_save_id($webhookID);

      } else {
        $webhookID = 0;
        return $webhookID;
      }

    }

  }


  /*

  Registering Webhooks

  */
  public function wps_webhooks_register() {

    $webhooks = array();

    // Products
    $webhooks['products/create'] = $this->wps_webhooks_register_single("products/create", "wps_webhooks_product_create");
    $webhooks['products/update'] = $this->wps_webhooks_register_single("products/update", "wps_webhooks_product_update");
    $webhooks['products/delete'] = $this->wps_webhooks_register_single("products/delete", "wps_webhooks_product_delete");

    // Collections
    $webhooks['collections/create'] = $this->wps_webhooks_register_single("collections/create", "wps_webhooks_collections_create");
    $webhooks['collections/update'] = $this->wps_webhooks_register_single("collections/update", "wps_webhooks_collections_update");
    $webhooks['collections/delete'] = $this->wps_webhooks_register_single("collections/delete", "wps_webhooks_collections_delete");

    // Shop
    $webhooks['shop/update'] = $this->wps_webhooks_register_single("shop/update", "wps_webhooks_shop_update");
    $webhooks['app/uninstalled'] = $this->wps_webhooks_register_single("app/uninstalled", "wps_webhooks_shop_app_uninstalled");


  }

}
