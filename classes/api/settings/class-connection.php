<?php

namespace WPS\API\Settings;


if (!defined('ABSPATH')) {
	exit;
}

use WPS\Messages;
use WPS\Utils;

class Connection extends \WPS\API {

	public $DB_Settings_Connection;
	public $DB_Settings_General;
	public $DB_Settings_Syncing;
	public $Shopify_API;


	public function __construct($DB_Settings_Connection, $DB_Settings_General, $DB_Settings_Syncing, $Shopify_API) {

		$this->DB_Settings_Connection				= $DB_Settings_Connection;
		$this->DB_Settings_General					= $DB_Settings_General;
		$this->DB_Settings_Syncing					= $DB_Settings_Syncing;
		$this->Shopify_API									= $Shopify_API;

	}


	public function only_valid_storefront_access_tokens($access_token, $user_entered_token) {
		return $access_token->access_token === $user_entered_token;
	}

	public function valid_storefront_access_tokens($storefront_access_tokens, $user_entered_token) {

		return array_filter($storefront_access_tokens, function($access_token) use( $user_entered_token ) {
			return $this->only_valid_storefront_access_tokens($access_token, $user_entered_token);
		});

	}


	/*

	Deletes a connection

	*/
	public function delete_connection($request) {

		return $this->handle_response([
			'response_multi' => [
				$this->DB_Settings_Connection->truncate(),
				$this->DB_Settings_General->reset_sync_by_collections()
			]
		]);

	}


	public function get_connection($request) {

		return $this->handle_response([
			'response' => $this->DB_Settings_Connection->get()
		]);

	}


	/*

	Insert connection data

	Called from

	*/
	public function set_connection($request) {

		return $this->handle_response([
			'response' => $this->DB_Settings_Connection->insert_connection( $request->get_param('connection') )
		]);

	}


	/*

	Checks either the web server connection or the Shopify connection

	*/
	public function check_connection($request) {

		if ($request->get_param('type') === 'shopify') {

			$response = $this->handle_response([
				'response' 		=> $this->Shopify_API->get_storefront_access_tokens(),
				'is_syncing' 	=> $request->get_param('is_syncing')
			]);


			if ( $this->is_handle_response_error($response) ) {
				return $response;
			}


			$form_creds = $request->get_param('creds');
			$valid_tokens = $this->valid_storefront_access_tokens($response->storefront_access_tokens, $form_creds['js_access_token']);


			if ( empty($valid_tokens) ) {

				return $this->handle_response([
					'response' => Utils::wp_error([
						'message_lookup' 	=> Messages::get('connection_invalid_storefront_access_token'),
						'call_method' 		=> __METHOD__,
						'call_line' 			=> __LINE__
					])
				]);

			}

			return $response;

		}

		// Defaults to checking for an open / valid webserver connection
		return $this->handle_response([
			'response' 		=> $this->check_server_connection(),
			'is_syncing' 	=> $request->get_param('is_syncing')
		]);

	}


	/*

	Checks for a valid (open) connection to the web server based on a URL. Useful to check
	whether the syncing will even work before starting ...

	WP Shopify addon

	@param string $url
	@return boolean

	*/
	public function check_server_connection() {

		$url = $_SERVER['HTTP_REFERER'];

		$url_parts = @parse_url($url);

		if (!$url_parts) return false;
		if (!isset($url_parts['host'])) return false; //can't process relative URLs
		if (!isset($url_parts['path'])) $url_parts['path'] = '/';

		$socket = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);

		if (!$socket) {

			return Utils::wp_error([
				'message_lookup' 	=> 'invalid_server_connection',
				'call_method' 		=> __METHOD__,
				'call_line' 			=> __LINE__
			]);

		} else {
			return true;
		}




	}


	/*

	Register route: collections_heading

	*/
  public function register_route_connection() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/connection', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_connection']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'set_connection']
			],
			[
				'methods'         => 'DELETE',
				'callback'        => [$this, 'delete_connection']
			]
		]);

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_connection_check() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/connection/check', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'check_connection']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

    add_action('rest_api_init', [$this, 'register_route_connection']);
		add_action('rest_api_init', [$this, 'register_route_connection_check']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
