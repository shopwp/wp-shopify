<?php

namespace WPS\API\Settings;


if (!defined('ABSPATH')) {
	exit;
}


class License extends \WPS\API {

  public $DB_Settings_License;
	public $HTTP;

	public function __construct($DB_Settings_License, $HTTP) {
		$this->DB_Settings_License 		= $DB_Settings_License;
		$this->HTTP										= $HTTP;
	}


	/*

	Get License Details

	*/
	public function get_license($request) {

		return $this->handle_response([
			'response' => $this->DB_Settings_License->get()
		]);

	}


	/*

	Set License Details

	*/
	public function set_license($request) {

		return $this->handle_response([
			'response' => $this->DB_Settings_License->insert_license( $request->get_param('license') )
		]);

	}


	/*

	Delete License Details

	*/
	public function delete_license($request) {

		return $this->handle_response([
			'response' => $this->deactivate_license( $this->DB_Settings_License->get_license() )
		]);

	}


	/*

	Deactivate License

	*/
	public function deactivate_license($license_key) {

		if (empty($license_key) || empty($license_key->license_key) ) {
			return false;
		}


		// Deletes the key locally
		$this->DB_Settings_License->truncate();

		$url = WPS_PLUGIN_ENV . '/edd-sl?edd_action=deactivate_license&item_name=' . WPS_PLUGIN_NAME_ENCODED . '&license=' . $license_key->license_key . '&url=' . home_url();

		return $this->HTTP->request('GET', $url);

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_license() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/license', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_license']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'set_license']
			],
			[
				'methods'         => 'DELETE',
				'callback'        => [$this, 'delete_license']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

    add_action('rest_api_init', [$this, 'register_route_license']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
