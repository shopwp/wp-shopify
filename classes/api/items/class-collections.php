<?php

namespace WPS\API\Items;

use WPS\Messages;
use WPS\Utils;
use WPS\Transients;
use WPS\CPT;

if (!defined('ABSPATH')) {
	exit;
}


class Collections extends \WPS\API {

	public function __construct($DB_Settings_General, $DB_Settings_Syncing, $DB_Settings_Connection, $Shopify_API, $Processing_Collections_Custom, $Processing_Collections_Smart, $Processing_Posts_Collections_Smart, $Processing_Posts_Collections_Custom) {

		$this->DB_Settings_General 										= $DB_Settings_General;
		$this->DB_Settings_Syncing 										= $DB_Settings_Syncing;
		$this->DB_Settings_Connection 								= $DB_Settings_Connection;
		$this->Shopify_API 														= $Shopify_API;

		$this->Processing_Collections_Smart						= $Processing_Collections_Smart;
		$this->Processing_Collections_Custom					= $Processing_Collections_Custom;

		$this->Processing_Posts_Collections_Smart 		= $Processing_Posts_Collections_Smart;
		$this->Processing_Posts_Collections_Custom 		= $Processing_Posts_Collections_Custom;

	}


	/*

	Get Smart Collections Count

	Nonce checks are handled automatically by WordPress

	*/
	public function get_smart_collections_count($request) {

		// Get smart collections count
		$response = $this->Shopify_API->get_smart_collections_count();

		return $this->handle_response([
			'response' 				=> $response,
			'access_prop' 		=> 'count',
			'return_key' 			=> 'smart_collections',
			'warning_message'	=> 'smart_collections_count_not_found'
		]);

	}

	public function custom_collections_meta_info() {

		return [
			'post_type' 			=> WPS_COLLECTIONS_POST_TYPE_SLUG,
			'increment_name' 	=> 'custom_collections'
		];

	}

	public function smart_collections_meta_info() {

		return [
			'post_type' 			=> WPS_COLLECTIONS_POST_TYPE_SLUG,
			'increment_name' 	=> 'smart_collections'
		];

	}


	/*

	Get Custom Collections

	Nonce checks are handled automatically by WordPress

	*/
	public function get_custom_collections($request) {

		$param_limit = $this->DB_Settings_General->get_items_per_request();

		// Grab custom collections from Shopify
		$response = $this->Shopify_API->get_custom_collections_per_page( $param_limit, $request->get_param('page') );

		$response->custom_collections = CPT::add_props_to_items($response->custom_collections, $this->custom_collections_meta_info() );

		return $this->handle_response([
			'response' 				=> $response,
			'access_prop' 		=> 'custom_collections',
			'return_key' 			=> 'custom_collections',
			'warning_message'	=> 'custom_collections_count_not_found',
			'meta'						=> $this->custom_collections_meta_info(),
			'process_fns' 		=> [
				$this->Processing_Collections_Custom,
				$this->Processing_Posts_Collections_Custom
			]
		]);

	}


	/*

	Get smart collections

	Nonce checks are handled automatically by WordPress

	*/
	public function get_smart_collections($request) {

		$param_limit = $this->DB_Settings_General->get_items_per_request();

		// Grab smart collections from Shopify
		$response = $this->Shopify_API->get_smart_collections_per_page( $param_limit, $request->get_param('page') );

		$response->smart_collections = CPT::add_props_to_items($response->smart_collections, $this->smart_collections_meta_info() );

		return $this->handle_response([
			'response' 				=> $response,
			'access_prop' 		=> 'smart_collections',
			'return_key' 			=> 'smart_collections',
			'warning_message'	=> 'smart_collections_count_not_found',
			'meta'						=> $this->smart_collections_meta_info(),
			'process_fns'			=> [
				$this->Processing_Collections_Smart,
				$this->Processing_Posts_Collections_Smart
			]
		]);

	}


	/*

	Get Custom Collections Count

	Nonce checks are handled automatically by WordPress

	*/
	public function get_custom_collections_count($request) {

		// Get custom collections count
		$response = $this->Shopify_API->get_custom_collections_count();

		return $this->handle_response([
			'response' 				=> $response,
			'access_prop' 		=> 'count',
			'return_key' 			=> 'custom_collections',
			'warning_message'	=> 'custom_collections_count_not_found'
		]);

	}


	/*

	Gets all collections

	*/
	public function get_all_collections($request) {

		if (!$this->DB_Settings_Connection->has_connection()) {
			$this->send_error( Messages::get('connection_not_found') . ' (get_all_collections)');
		}

		$collections_cache = Transients::get('wps_sync_by_collections');

		if ( !empty($collections_cache) ) {

			return $this->handle_response([
				'response' => maybe_unserialize($collections_cache)
			]);

		}


		$param_limit 					= $this->DB_Settings_General->get_items_per_request();
		$smart_collections 		= $this->Shopify_API->get_smart_collections_per_page($param_limit, 1);
		$custom_collections 	= $this->Shopify_API->get_custom_collections_per_page($param_limit, 1);


		if (Utils::has($smart_collections, 'errors')) {

			return $this->handle_response([
				'response' => $smart_collections
			]);

		}

		if (Utils::has($custom_collections, 'errors')) {

			return $this->handle_response([
				'response' => $custom_collections
			]);

		}


		$collections_merged = array_merge($smart_collections->smart_collections, $custom_collections->custom_collections);

		if (!empty($collections_merged)) {

			$collections_merged_final_reduced = array_map( function($collection) {

				$new_collection_obj = new \stdClass();
				$new_collection_obj->id = $collection->id;
				$new_collection_obj->title = $collection->title;

				return $new_collection_obj;

			}, $collections_merged);

			$collections_serialized = maybe_serialize($collections_merged_final_reduced);

			Transients::set('wps_sync_by_collections', $collections_serialized);

			return $this->handle_response([
				'response' 				=> $collections_merged
			]);

		}


	}



	public function insert_collections_posts() {

		return $this->handle_response([
			'response' 				=> CPT::get_all_posts_truncated(WPS_COLLECTIONS_POST_TYPE_SLUG, ['ID', 'post_name']),
			'process_fns'			=> [
				$this->Processing_Posts
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_collections_posts() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/collections/posts', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'insert_collections_posts']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_smart_collections_count() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/smart_collections/count', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'get_smart_collections_count']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_custom_collections_count() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/custom_collections/count', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'get_custom_collections_count']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_smart_collections() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/smart_collections', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'get_smart_collections']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_custom_collections() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/custom_collections', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'get_custom_collections']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_collections() {

		return register_rest_route( WPS_SHOPIFY_API_NAMESPACE, '/collections', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'get_all_collections']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('rest_api_init', [$this, 'register_route_smart_collections_count']);
		add_action('rest_api_init', [$this, 'register_route_smart_collections']);

		add_action('rest_api_init', [$this, 'register_route_custom_collections_count']);
		add_action('rest_api_init', [$this, 'register_route_custom_collections']);

		add_action('rest_api_init', [$this, 'register_route_collections']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
