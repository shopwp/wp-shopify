<?php

namespace WPS;

use WPS\DB\Settings_Connection;

// require_once plugin_dir_path( __FILE__ ) . '../admin/class-admin.php';

/*

Class Waypoint

*/
class Waypoints {

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

  Get Shopify API settings from Waypoint
  TODO: Add license key checks

  */
  public function wps_waypoint_settings() {

    $response = \Requests::get('https://staging.wpshop.io/wp-json/wp-shopify/v1/settings');
    $responseBody = $response->body;

    return $responseBody;

  }


  /*

	Send user to Shopify

	*/
	public function wps_waypoint_get_shopify_url() {

		$shopifySettings = json_decode( $this->wps_waypoint_settings() );
    // $connection = $_POST['connection'];

    // OLD
    $connection = $this->config->wps_get_settings_connection();

		$url = 'https://' . $connection->domain . '/admin/oauth/authorize?client_id=' . $shopifySettings->wps_api_key . '&scope=' . $shopifySettings->wps_scopes . '&redirect_uri=' . $shopifySettings->wps_redirect . '&state=' . $connection->nonce;

    echo $url;
		die();

	}


  /*

	Grab the list of connected WP Shopify clients

	*/
	function wps_waypoint_clients($token) {

		$headers = array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer ' . $token
		);

		$responseUser = \Requests::get('https://staging.wpshop.io/wp-json/wp/v2/users/2', $headers);

		$userFinal = $responseUser->body;
		$userDesc = json_decode($userFinal);
		$wpShopifyClients = json_decode($userDesc->description);

		return $wpShopifyClients;

	}


  /*

	Generate a WP API auth token
  TODO: Remove hardcoded credentials

	*/
	public function wps_waypoint_auth() {

		$data = array(
      'username' => 'wp-shopify-auth-user',
      'password' => 'xyWlcxyIwkA(#gUl!Exy$ITz'
    );

		$response = \Requests::post('https://staging.wpshop.io/wp-json/jwt-auth/v1/token', array(), $data);

		$token = json_decode($response->body)->token;

		return $token;

	}


  /*

  Get Shopify access token

  */
  public function wps_waypoint_get_access_token($accessTokenData) {

    $url = 'https://' . $accessTokenData['shop'] . '/admin/oauth/access_token';

    $data = array(
      'client_id' => $accessTokenData['client_id'],
      'client_secret' => $accessTokenData['client_secret'],
      'code' => $accessTokenData['code']
    );

    $response = \Requests::post($url, array(), $data);
    $data = json_decode($response->body);

    return $data->access_token;

  }


  /*

	Saving access token to plugin settings

	*/
	public function wps_waypoint_save_access_token($accessToken) {

    $DB_Settings_Connection = new Settings_Connection();

    //
    // OLD
    //
    // $pluginOptions = $this->config->wps_get_settings_connection();
    // $pluginOptionsKey = $this->config->settings_connection_option_name;
    //
		// $pluginOptions['access_token'] = $accessToken;
    //
		// update_option($pluginOptionsKey, $pluginOptions);

    $DB_Settings_Connection->update(1, array(
      'access_token' => $accessToken
    ));

	}


  /*

  Return clients with matching domain and nonce

  */
  public function wps_waypoint_filter_clients($clients) {

    $connection = $this->config->wps_get_settings_connection();

    $storedDomain = $connection->domain;

    foreach ($clients as $client) {

      if($client->shop === $storedDomain) {

        if($client->nonce === $connection->nonce) {
          return $client;
        }

      } else {
        return null;

      }

    }

  }


}
