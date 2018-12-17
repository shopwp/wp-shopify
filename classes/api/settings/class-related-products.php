<?php

namespace WPS\API\Settings;


if (!defined('ABSPATH')) {
	exit;
}


class Related_Products extends \WPS\API {

  public $DB_Settings_General;

	public function __construct($DB_Settings_General) {
		$this->DB_Settings_General = $DB_Settings_General;
	}


  /*

  Update setting: related_products_heading

  */
  public function update_setting_related_products_heading($request) {

    $heading = $request->get_param('value');

    if ( !is_string($heading) ) {
      return $this->error( $request->get_route(), 'Failed to update related products heading due to invalid type', 500);
    }

    $update_result = $this->DB_Settings_General->update_related_products_heading($heading);

    if (!$update_result) {
      return $this->error( $request->get_route(), 'Failed to update related products heading', 500);
    }

    return $update_result;

  }


  /*

  Update setting: related_products_heading

  */
  public function update_setting_related_products_heading_toggle($request) {

    $heading = $request->get_param('value');

    if ( is_string($heading) ) {
      return $this->error( $request->get_route(), 'Failed to update related products heading toggle due to invalid type', 500);
    }

    $update_result = $this->DB_Settings_General->update_related_products_heading_toggle($heading);

    if (!$update_result) {
      return $this->error( $request->get_route(), 'Failed to update related products heading toggle', 500);
    }

    return $update_result;

  }


  /*

	Update setting: related_products_heading

	*/
	public function update_setting_related_products_images_sizing_toggle($request) {

		$toggle = $request->get_param('value');

		if ( is_string($toggle) ) {
			return $this->error( $request->get_route(), 'Failed to update related_products images sizing toggle due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_related_products_images_sizing_toggle($toggle);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update related_products images sizing toggle', 500);
		}

		return $update_result;

	}


	/*

	Update setting: related_related_products_heading

	*/
	public function update_setting_related_products_images_sizing_width($request) {

		$width = $request->get_param('value');

		if ( is_string($width) ) {
			return $this->error( $request->get_route(), 'Failed to update related_products images sizing width due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_related_products_images_sizing_width($width);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update related_products images sizing width', 500);
		}

		return $update_result;

	}


	/*

	Update setting: related_related_products_heading

	*/
	public function update_setting_related_products_images_sizing_height($request) {

		$height = $request->get_param('value');

		if ( is_string($height) ) {
			return $this->error( $request->get_route(), 'Failed to update related_products images sizing height due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_related_products_images_sizing_height($height);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update related_products images sizing height', 500);
		}

		return $update_result;

	}


	/*

	Update setting: related_related_products_heading

	*/
	public function update_setting_related_products_images_sizing_crop($request) {

		$crop = $request->get_param('value');

		if ( !is_string($crop) ) {
			return $this->error( $request->get_route(), 'Failed to update related_products images sizing crop due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_related_products_images_sizing_crop($crop);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update related_products images sizing crop', 500);
		}

		return $update_result;

	}


	/*

	Update setting: related_related_products_heading

	*/
	public function update_setting_related_products_images_sizing_scale($request) {

		$scale = $request->get_param('value');

		if ( !is_int($scale) ) {
			return $this->error( $request->get_route(), 'Failed to update related_products images scale value due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_related_products_images_sizing_scale($scale);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update related_products images scale value', 500);
		}

		return $update_result;

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_related_products_heading() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/related_products_heading', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_related_products_heading']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_related_products_heading']
			]
		]);

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_related_products_heading_toggle() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/related_products_heading_toggle', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_related_products_heading_toggle']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_related_products_heading_toggle']
			]
		]);

	}


  /*

	Register route: related_products_heading

	*/
  public function register_route_related_products_images_sizing_toggle() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/related_products_images_sizing_toggle', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_related_products_images_sizing_toggle']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_related_products_images_sizing_toggle']
			]
		]);

	}


	/*

	Register route: related_products_heading

	*/
  public function register_route_related_products_images_sizing_width() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/related_products_images_sizing_width', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_related_products_images_sizing_width']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_related_products_images_sizing_width']
			]
		]);

	}


	/*

	Register route: related_products_heading

	*/
  public function register_route_related_products_images_sizing_height() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/related_products_images_sizing_height', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_related_products_images_sizing_height']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_related_products_images_sizing_height']
			]
		]);

	}


	/*

	Register route: related_products_heading

	*/
  public function register_route_related_products_images_sizing_crop() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/related_products_images_sizing_crop', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_related_products_images_sizing_crop']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_related_products_images_sizing_crop']
			]
		]);

	}


	/*

	Register route: related_products_heading

	*/
  public function register_route_related_products_images_sizing_scale() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/settings/related_products_images_sizing_scale', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_related_products_images_sizing_scale']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_related_products_images_sizing_scale']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

    add_action('rest_api_init', [$this, 'register_route_related_products_heading_toggle']);
		add_action('rest_api_init', [$this, 'register_route_related_products_heading']);

		add_action('rest_api_init', [$this, 'register_route_related_products_images_sizing_toggle']);
		add_action('rest_api_init', [$this, 'register_route_related_products_images_sizing_width']);
		add_action('rest_api_init', [$this, 'register_route_related_products_images_sizing_height']);
		add_action('rest_api_init', [$this, 'register_route_related_products_images_sizing_crop']);
		add_action('rest_api_init', [$this, 'register_route_related_products_images_sizing_scale']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
