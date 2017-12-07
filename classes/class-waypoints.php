<?php

namespace WPS;
require plugin_dir_path( __FILE__ ) . '../vendor/autoload.php';

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

use WPS\WS;
use WPS\Messages;
use WPS\DB\Settings_Connection;
use GuzzleHttp\Client as Guzzle;

/*

Class Waypoint

*/
class Waypoints {

  protected static $instantiated = null;
  private $Config;
  private $WS;
  private $messages;

	/*

	Initialize the class and set its properties.

	*/
	public function __construct($Config) {
		$this->config = $Config;
    $this->WS = new WS($this->config);
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

  Get Shopify API settings from Waypoint
  TODO: Add license key checks

  */
  public function wps_waypoint_settings() {

    $url = 'https://wpshop.io/wp-json/wp-shopify/v1/settings';

    try {

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url);

      $shopDataResponse = $guzzelResponse->getBody()->getContents();

      return $shopDataResponse;

    } catch (RequestException $error) {

      return $this->WS->wps_get_error_message($error) . ' (Error code: #1052a)';

    }

  }


  /*

	Send user to Shopify

	*/
	public function wps_waypoint_get_shopify_url() {

    Utils::valid_backend_nonce($_POST['nonce']) ?: wp_send_json_error($this->messages->message_nonce_invalid . ' (Error code: #1048a)');

		$shopifySettings = json_decode( $this->wps_waypoint_settings() );
    // $connection = $_POST['connection'];

    // OLD
    $connection = $this->config->wps_get_settings_connection();

    if (is_object($connection) && $connection) {
      $url = 'https://' . $connection->domain . '/admin/oauth/authorize?client_id=' . $shopifySettings->wps_api_key . '&scope=' . $shopifySettings->wps_scopes . '&redirect_uri=' . $shopifySettings->wps_redirect . '&state=' . $connection->nonce;

      wp_send_json_success($url);

    } else {
      wp_send_json_error($this->messages->message_connection_not_found . ' (Error code: #1048b)');

    }

	}


  /*

	Grab the list of connected WP Shopify clients

	*/
	function wps_waypoint_clients($token) {

    $url = 'https://wpshop.io/wp-json/wp/v2/users/2';

		$headers = array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer ' . $token
		);

    try {

      $Guzzle = new Guzzle();
      $guzzelResponse = $Guzzle->request('GET', $url, array(
        'headers' => $headers
      ));

      $data = $guzzelResponse->getBody()->getContents();
      $userDesc = json_decode($data);

      $wpShopifyClients = json_decode($userDesc->description);

  		return $wpShopifyClients;


    } catch (\Exception $error) {

      return $this->WS->wps_get_error_message($error) . ' (Error code: #1049a)';

    }


	}


  /*

	Generate a WP API auth token
  TODO!: Remove hardcoded credentials

	*/
	public function wps_waypoint_auth() {

    $url = 'https://wpshop.io/wp-json/jwt-auth/v1/token';

		$data = array(
      'username' => 'wp-shopify-auth-user',
      'password' => 'xyWlcxyIwkA(#gUl!Exy$ITz'
    );

    $Guzzle = new Guzzle();

    try {

      $guzzelResponse = $Guzzle->post($url, [
        'query' => $data
      ]);

      return json_decode($guzzelResponse->getBody()->getContents())->token;

    } catch (\Exception $error) {

      return $this->WS->wps_get_error_message($error) . ' (Error code: #1050a)';

    }

	}


  /*

  Get Shopify access token

  */
  public function wps_waypoint_get_access_token($accessTokenData) {

    $api_url = 'https://' . $accessTokenData['shop'] . '/admin/oauth/access_token';

    $api_body = array(
      'client_id' => $accessTokenData['client_id'],
      'client_secret' => $accessTokenData['client_secret'],
      'code' => $accessTokenData['code']
    );

    $Guzzle = new Guzzle();

    $headers = array(
      'Accept' => 'application/json',
      'Content-type' => 'application/json'
    );

    try {

      $guzzelResponse = $Guzzle->post($api_url, [
        'query' => $api_body,
        'headers' => $headers
      ]);

      return json_decode($guzzelResponse->getBody()->getContents());

    } catch (\Exception $error) {

      return $this->WS->wps_get_error_message($error) . ' (Error code: #1051a)';

    }


  }


  /*

	Saving access token to plugin settings

	*/
	public function wps_waypoint_save_access_token($accessToken) {

    $DB_Settings_Connection = new Settings_Connection();

    if (is_object($accessToken)) {

      $DB_Settings_Connection->update(1, array(
        'access_token' => $accessToken->access_token
      ));

    } else {
      return false;

    }

	}


  /*

  Return clients with matching domain and nonce

  */
  public function wps_waypoint_filter_clients($clients) {

    $connection = $this->config->wps_get_settings_connection();

    if (is_object($connection) && property_exists($connection, 'domain')) {

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

    } else {
      return null;
    }

  }

}
