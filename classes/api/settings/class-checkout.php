<?php

namespace WPS\API\Settings;


if (!defined('ABSPATH')) {
	exit;
}


class Checkout extends \WPS\API {

  public $DB_Settings_General;

	public function __construct($DB_Settings_General) {
		$this->DB_Settings_General = $DB_Settings_General;
	}


	/*

	Update setting: add_to_cart_color

	*/
	public function update_setting_enable_custom_checkout_domain($request) {

		$value = $request->get_param('value');


		if ( !is_bool($value) ) {
			return $this->error( $request->get_route(), 'Failed to update custom checkout domain due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_enable_custom_checkout_domain($value);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update custom checkout domain ', 500);
		}

		return $update_result;

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_enable_custom_checkout_domain() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/settings/checkout_enable_custom_checkout_domain', [
			[
				'methods'         => \WP_REST_Server::READABLE,
				'callback'        => [$this, 'get_setting_enable_custom_checkout_domain']
			],
			[
				'methods'         => \WP_REST_Server::CREATABLE,
				'callback'        => [$this, 'update_setting_enable_custom_checkout_domain']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('rest_api_init', [$this, 'register_route_enable_custom_checkout_domain']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
