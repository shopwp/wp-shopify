<?php

namespace WPS\API\Settings;


if (!defined('ABSPATH')) {
	exit;
}


class Collections extends \WPS\API {

  public $DB_Settings_General;

	public function __construct($DB_Settings_General) {
		$this->DB_Settings_General = $DB_Settings_General;
	}


	/*

	Update setting: collections_heading

	*/
	public function update_setting_collections_heading_toggle($request) {

		$enabled = $request->get_param('value');

		if ( is_string($enabled) ) {
			return $this->error( $request->get_route(), 'Failed to update collections heading toggle due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_collections_heading_toggle($enabled);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update collections heading toggle', 500);
		}

		return $update_result;

	}


	/*

	Update setting: collections_heading

	*/
	public function update_setting_collections_heading($request) {

		$heading = $request->get_param('value');

		if ( !is_string($heading) ) {
			return $this->error( $request->get_route(), 'Failed to update collections heading due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_collections_heading($heading);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update collections heading', 500);
		}

		return $update_result;

	}


	/*

	Update setting: related_products_heading

	*/
	public function update_setting_collections_images_sizing_toggle($request) {

		$toggle = $request->get_param('value');

		if ( is_string($toggle) ) {
			return $this->error( $request->get_route(), 'Failed to update collections images sizing toggle due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_collections_images_sizing_toggle($toggle);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update collections images sizing toggle', 500);
		}

		return $update_result;

	}


	/*

	Update setting: related_collections_heading

	*/
	public function update_setting_collections_images_sizing_width($request) {

		$width = $request->get_param('value');

		if ( is_string($width) ) {
			return $this->error( $request->get_route(), 'Failed to update collections images sizing width due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_collections_images_sizing_width($width);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update collections images sizing width', 500);
		}

		return $update_result;

	}


	/*

	Update setting: related_collections_heading

	*/
	public function update_setting_collections_images_sizing_height($request) {

		$height = $request->get_param('value');

		if ( is_string($height) ) {
			return $this->error( $request->get_route(), 'Failed to update collections images sizing height due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_collections_images_sizing_height($height);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update collections images sizing height', 500);
		}

		return $update_result;

	}


	/*

	Update setting: related_collections_heading

	*/
	public function update_setting_collections_images_sizing_crop($request) {

		$crop = $request->get_param('value');

		if ( !is_string($crop) ) {
			return $this->error( $request->get_route(), 'Failed to update collections images sizing crop due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_collections_images_sizing_crop($crop);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update collections images sizing crop', 500);
		}

		return $update_result;

	}


	/*

	Update setting: related_collections_heading

	*/
	public function update_setting_collections_images_sizing_scale($request) {

		$scale = $request->get_param('value');

		if ( !is_int($scale) ) {
			return $this->error( $request->get_route(), 'Failed to update collections images scale value due to invalid type', 500);
		}

		$update_result = $this->DB_Settings_General->update_collections_images_sizing_scale($scale);

		if (!$update_result) {
			return $this->error( $request->get_route(), 'Failed to update collections images scale value', 500);
		}

		return $update_result;

	}



	public function get_setting_selected_collections($request) {

		$collections = $this->DB_Settings_General->sync_by_collections();

		return $this->handle_response([
			'response' => maybe_unserialize($collections)
		]);

	}





	/*

	Register route: collections_heading

	*/
  public function register_route_collections_heading_toggle() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/settings/collections_heading_toggle', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_collections_heading_toggle']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_collections_heading_toggle']
			]
		]);

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_collections_heading() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/settings/collections_heading', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_collections_heading']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_collections_heading']
			]
		]);

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_collections_images_sizing_toggle() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/settings/collections_images_sizing_toggle', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_collections_images_sizing_toggle']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_collections_images_sizing_toggle']
			]
		]);

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_collections_images_sizing_width() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/settings/collections_images_sizing_width', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_collections_images_sizing_width']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_collections_images_sizing_width']
			]
		]);

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_collections_images_sizing_height() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/settings/collections_images_sizing_height', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_collections_images_sizing_height']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_collections_images_sizing_height']
			]
		]);

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_collections_images_sizing_crop() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/settings/collections_images_sizing_crop', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_collections_images_sizing_crop']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_collections_images_sizing_crop']
			]
		]);

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_collections_images_sizing_scale() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/settings/collections_images_sizing_scale', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_collections_images_sizing_scale']
			],
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'update_setting_collections_images_sizing_scale']
			]
		]);

	}


	/*

	Register route: collections_heading

	*/
  public function register_route_selected_collections() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/settings/selected_collections', [
			[
				'methods'         => 'GET',
				'callback'        => [$this, 'get_setting_selected_collections']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

    add_action('rest_api_init', [$this, 'register_route_collections_heading_toggle']);
		add_action('rest_api_init', [$this, 'register_route_collections_heading']);

		add_action('rest_api_init', [$this, 'register_route_collections_images_sizing_toggle']);
		add_action('rest_api_init', [$this, 'register_route_collections_images_sizing_width']);
		add_action('rest_api_init', [$this, 'register_route_collections_images_sizing_height']);
		add_action('rest_api_init', [$this, 'register_route_collections_images_sizing_crop']);
		add_action('rest_api_init', [$this, 'register_route_collections_images_sizing_scale']);

		add_action('rest_api_init', [$this, 'register_route_selected_collections']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
