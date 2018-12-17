<?php

namespace WPS\API\Settings;


if (!defined('ABSPATH')) {
	exit;
}


class Products extends \WPS\API {

  public $DB_Settings_General;

	public function __construct($DB_Settings_General) {
		$this->DB_Settings_General = $DB_Settings_General;
	}


  /*

  Update setting: add_to_cart_color

  */
  public function update_setting_products_add_to_cart_color($request) {

    $color = $request->get_param('color');

    if ( !is_string($color) ) {
      return $this->send_error('Failed to update add to cart button color due to invalid type');
    }

    $update_result = $this->DB_Settings_General->update_add_to_cart_color($color);

    if (!$update_result) {
      return $this->send_error('Failed to update add to cart button color');
    }

    return $update_result;

  }


  /*

  Update setting: add_to_cart_color

  */
  public function update_setting_products_variant_color($request) {

    $color = $request->get_param('color');

    if ( !is_string($color) ) {
      return $this->send_error('Failed to update variant color due to invalid type');
    }

    $update_result = $this->DB_Settings_General->update_variant_color($color);

    if (!$update_result) {
      return $this->send_error('Failed to update variant color');
    }

    return $update_result;

  }


  /*

	Update setting: add_to_cart_color

	*/
	public function update_setting_products_heading_toggle($request) {

		$enabled = $request->get_param('value');

		if ( is_string($enabled) ) {
			return $this->send_error('Failed to update products heading toggle due to invalid type');
		}

		$update_result = $this->DB_Settings_General->update_products_heading_toggle($enabled);

		if (!$update_result) {
			return $this->send_error('Failed to update products heading toggle');
		}

		return $update_result;

	}


	/*

	Update setting: products_heading

	*/
	public function update_setting_products_heading($request) {

		$heading = $request->get_param('value');

		if ( !is_string($heading) ) {
			return $this->send_error('Failed to update products heading due to invalid type');
		}

		$update_result = $this->DB_Settings_General->update_products_heading($heading);

		if (!$update_result) {
			return $this->send_error('Failed to update products heading');
		}

		return $update_result;

	}


	/*

	Update setting: related_products_heading

	*/
	public function update_setting_products_images_sizing_toggle($request) {

		$toggle = $request->get_param('value');

		if ( is_string($toggle) ) {
			return $this->send_error('Failed to update products images sizing toggle due to invalid type');
		}

		$update_result = $this->DB_Settings_General->update_products_images_sizing_toggle($toggle);

		if (!$update_result) {
			return $this->send_error('Failed to update products images sizing toggle');
		}

		return $update_result;

	}


	/*

	Update setting: related_products_heading

	*/
	public function update_setting_products_images_sizing_width($request) {

		$width = $request->get_param('value');

		if ( is_string($width) ) {
			return $this->send_error('Failed to update products images sizing width due to invalid type');
		}

		$update_result = $this->DB_Settings_General->update_products_images_sizing_width($width);

		if (!$update_result) {
			return $this->send_error('Failed to update products images sizing width');
		}

		return $update_result;

	}


	/*

	Update setting: related_products_heading

	*/
	public function update_setting_products_images_sizing_height($request) {

		$height = $request->get_param('value');

		if ( is_string($height) ) {
			return $this->send_error('Failed to update products images sizing height due to invalid type');
		}

		$update_result = $this->DB_Settings_General->update_products_images_sizing_height($height);

		if (!$update_result) {
			return $this->send_error('Failed to update products images sizing height');
		}

		return $update_result;

	}


	/*

	Update setting: related_products_heading

	*/
	public function update_setting_products_images_sizing_crop($request) {

		$crop = $request->get_param('value');

		if ( !is_string($crop) ) {
			return $this->send_error('Failed to update products images sizing crop due to invalid type');
		}

		$update_result = $this->DB_Settings_General->update_products_images_sizing_crop($crop);

		if (!$update_result) {
			return $this->send_error('Failed to update products images sizing crop');
		}

		return $update_result;

	}


	/*

	Update setting: related_products_heading

	*/
	public function update_setting_products_images_sizing_scale($request) {

		$scale = $request->get_param('value');

		if ( !is_int($scale) ) {
			return $this->send_error('Failed to update products images scale value due to invalid type');
		}

		$update_result = $this->DB_Settings_General->update_products_images_sizing_scale($scale);

		if (!$update_result) {
			return $this->send_error('Failed to update products images scale value');
		}

		return $update_result;

	}


	/*

	Update setting: related_products_heading

	*/
	public function update_setting_products_compare_at($request) {

		$value = $request->get_param('value');

		if ( !is_bool($value) ) {
			return $this->send_error('Failed to update products compare at value due to invalid type');
		}

		$update_result = $this->DB_Settings_General->update_products_compare_at($value);

		if (!$update_result) {
			return $this->send_error('Failed to update products compare at value');
		}

		return $update_result;

	}


	/*

	Register route: add_to_cart_color

	*/
  public function register_route_products_add_to_cart_color() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/products_add_to_cart_color', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_add_to_cart_color']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_products_add_to_cart_color']
			]
		]);

	}


	/*

	Register route: variant_color

	*/
  public function register_route_products_variant_color() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/products_variant_color', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_products_variant_color']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_products_variant_color']
			]
		]);

	}


  /*

	Register route: products_heading

	*/
  public function register_route_products_heading_toggle() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/products_heading_toggle', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_products_heading_toggle']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_products_heading_toggle']
			]
		]);

	}


	/*

	Register route: products_heading

	*/
  public function register_route_products_heading() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/products_heading', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_products_heading']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_products_heading']
			]
		]);

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_products_images_sizing_toggle() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/products_images_sizing_toggle', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_products_images_sizing_toggle']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_products_images_sizing_toggle']
			]
		]);

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_products_images_sizing_width() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/products_images_sizing_width', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_products_images_sizing_width']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_products_images_sizing_width']
			]
		]);

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_products_images_sizing_height() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/products_images_sizing_height', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_products_images_sizing_height']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_products_images_sizing_height']
			]
		]);

	}


	/*

	Register route: register_route_products_images_sizing_crop

	*/
  public function register_route_products_images_sizing_crop() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/products_images_sizing_crop', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_products_images_sizing_crop']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_products_images_sizing_crop']
			]
		]);

	}


	/*

	Register route: register_route_products_images_sizing_scale

	*/
  public function register_route_products_images_sizing_scale() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/products_images_sizing_scale', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_products_images_sizing_scale']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_products_images_sizing_scale']
			]
		]);

	}


	/*

	Register route: register_route_products_compare_at

	*/
  public function register_route_products_compare_at() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/products_compare_at', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_products_compare_at']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_products_compare_at']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

    add_action('rest_api_init', [$this, 'register_route_products_add_to_cart_color']);
		add_action('rest_api_init', [$this, 'register_route_products_variant_color']);

    add_action('rest_api_init', [$this, 'register_route_products_heading_toggle']);
		add_action('rest_api_init', [$this, 'register_route_products_heading']);

		add_action('rest_api_init', [$this, 'register_route_products_images_sizing_toggle']);
		add_action('rest_api_init', [$this, 'register_route_products_images_sizing_width']);
		add_action('rest_api_init', [$this, 'register_route_products_images_sizing_height']);
		add_action('rest_api_init', [$this, 'register_route_products_images_sizing_crop']);
		add_action('rest_api_init', [$this, 'register_route_products_images_sizing_scale']);

		add_action('rest_api_init', [$this, 'register_route_products_compare_at']);


	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
