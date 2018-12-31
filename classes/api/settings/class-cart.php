<?php

namespace WPS\API\Settings;


if (!defined('ABSPATH')) {
	exit;
}


class Cart extends \WPS\API {

  public $DB_Settings_General;

	public function __construct($DB_Settings_General) {
		$this->DB_Settings_General = $DB_Settings_General;
	}


	/*

	Update setting: add_to_cart_color

	*/
	public function update_setting_checkout_color($request) {

		$color = $request->get_param('color');

		if ( !is_string($color) ) {
			return $this->error( $request->get_route(), 'Failed to update checkout button color due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_checkout_color($color);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update checkout button color', 500);
		}

		return $update_result;

	}


	/*

	Update setting: add_to_cart_color

	*/
	public function update_setting_cart_counter_color($request) {

		$color = $request->get_param('color');

		if ( !is_string($color) ) {
			return $this->error( $request->get_route(), 'Failed to update cart icon color due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_cart_counter_color($color);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update cart icon color', 500);
		}

		return $update_result;

	}


	/*

	Update setting: add_to_cart_color

	*/
	public function update_setting_cart_icon_color($request) {

		$color = $request->get_param('color');

		if ( !is_string($color) ) {
			return $this->error( $request->get_route(), 'Failed to update cart icon color due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_cart_icon_color($color);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update cart icon color', 500);
		}

		return $update_result;

	}


	/*

	Update setting: add_to_cart_color

	*/
	public function update_setting_cart_icon_fixed_color($request) {

		$color = $request->get_param('color');

		if ( !is_string($color) ) {
			return $this->error( $request->get_route(), 'Failed to update cart fixed icon color due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_cart_icon_fixed_color($color);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update cart fixed icon color', 500);
		}

		return $update_result;

	}


	/*

	Register route: checkout_color

	*/
  public function register_route_cart_checkout_color() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/settings/cart_checkout_color', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_checkout_color']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_checkout_color']
			]
		]);

	}


	/*

	Register route: cart_counter_color

	*/
  public function register_route_cart_counter_color() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/settings/cart_counter_color', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_cart_counter_color']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_cart_counter_color']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_cart_icon_color() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/settings/cart_icon_color', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_cart_icon_color']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_cart_icon_color']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_cart_icon_fixed_color() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/settings/cart_icon_fixed_color', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_cart_icon_fixed_color']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_cart_icon_fixed_color']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('rest_api_init', [$this, 'register_route_cart_checkout_color']);
		add_action('rest_api_init', [$this, 'register_route_cart_counter_color']);
		add_action('rest_api_init', [$this, 'register_route_cart_icon_color']);
		add_action('rest_api_init', [$this, 'register_route_cart_icon_fixed_color']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
