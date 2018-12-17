<?php

namespace WPS\API\Items;

use WPS\Messages;
use WPS\Utils;
use WPS\Utils\Data as Utils_Data;

if (!defined('ABSPATH')) {
	exit;
}


class Collects extends \WPS\API {

	public function __construct($DB_Settings_General, $DB_Settings_Syncing, $Shopify_API, $Processing_Collects) {
		$this->DB_Settings_General 			= $DB_Settings_General;
		$this->DB_Settings_Syncing 			= $DB_Settings_Syncing;
		$this->Shopify_API 							= $Shopify_API;
		$this->Processing_Collects			= $Processing_Collects;
	}


	/*

	Responsible for getting an array of API endpoints for given collection ids

	*/
	public function get_collects_count_by_collection_ids() {

		$collects_count = [];
		$collection_ids = $this->DB_Settings_General->get_sync_by_collections_ids();

		foreach ($collection_ids as $collection_id) {

			$count = $this->Shopify_API->get_collects_count_by_collection_id($collection_id);

			if ( Utils::has($count, 'count') ) {
				$collects_count[] = $count->count;
			}


		}

		return Utils::convert_array_to_object([
			'count' => array_sum($collects_count)
		]);

	}


	/*

	Get Collections Count

	*/
	public function get_collects_count($request) {

		// User is syncing by collection
		if ( $this->DB_Settings_General->is_syncing_by_collection() ) {

			return $this->handle_response([
				'response' 				=> $this->get_collects_count_by_collection_ids(),
				'access_prop'			=> 'count',
				'return_key' 			=> 'collects',
				'warning_message'	=> 'collects_count_not_found'
			]);

		}

		return $this->handle_response([
			'response' 				=> $this->Shopify_API->get_collects_count(),
			'access_prop'			=> 'count',
			'return_key' 			=> 'collects',
			'warning_message'	=> 'collects_count_not_found'
		]);


	}








	public function normalize_collects_response($response) {

		if ( is_array($response) ) {
			return $response;
		}

		if ( is_object($response) && property_exists($response, 'collects') ) {
			return $response->collects;
		}

	}




	public function get_collects_from_collections($current_page) {

		$collects					= [];
		$collection_ids 	= maybe_unserialize( $this->DB_Settings_General->sync_by_collections() );
		$param_limit 			= $this->DB_Settings_General->get_items_per_request();

		foreach ($collection_ids as $collection_id) {

			$result = $this->Shopify_API->get_collects_from_collection_per_page($collection_id, $param_limit, $current_page);

			if (is_wp_error($result)) {
				return $result;
				break;
			}

			$result = $this->normalize_collects_response($result);

			$collects = array_merge($collects, $result);

		}

		return Utils::convert_array_to_object([
			'collects' => $collects
		]);

	}


	public function get_collects_per_page($current_page) {
		return $this->Shopify_API->get_collects_per_page( $this->DB_Settings_General->get_items_per_request(), $current_page);
	}



	/*

	Get Collects

	Runs for each "page" of the Shopify API

	*/
	public function get_collects($request) {

		// Check if user is syncing from collections -- returns proper products
		if ( $this->DB_Settings_General->is_syncing_by_collection() ) {
			$response = $this->get_collects_from_collections( $request->get_param('page') );

		} else {
			$response = $this->get_collects_per_page( $request->get_param('page') );
		}

		return $this->handle_response([
			'response' 				=> $response,
			'access_prop'			=> 'collects',
			'return_key' 			=> 'collects',
			'warning_message'	=> 'missing_collects_for_page',
			'process_fns'			=> [
				$this->Processing_Collects
			]
		]);

	}


	/*

	Responsible for adding collects to $data

	*/
	public function add_collects_to_item($item, $collects) {

		$item->collects = $collects;

		return $item;

	}


	public function find_collects_to_add($collects) {

		if ( is_wp_error($collects) || Utils::object_is_empty($collects) ) {
			$collects_to_add = [];

		} else {
			$collects_to_add = $collects->collects;
		}

		return $collects_to_add;

	}


	/*

	Get a list of collects by product ID

	*/
	public function get_collects_from_product($item = null) {

		return $this->add_collects_to_item(
			$item,
			$this->find_collects_to_add( $this->Shopify_API->get_collects_by_product_id($item->id) )
		);

	}


	/*

	Get a list of collects by collection ID

	*/
	public function get_collects_from_collection($item) {

		return $this->add_collects_to_item(
			$item,
			$this->find_collects_to_add( $this->Shopify_API->get_collects_from_collection_id($item->id) )
		);

	}























	/*

	Register route: cart_icon_color

	*/
  public function register_route_collects_count() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/collects/count', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'get_collects_count']
			]
		]);

	}


	/*

	Register route: cart_icon_color

	*/
  public function register_route_collects() {

		return register_rest_route( WP_SHOPIFY_API_NAMESPACE, '/collects', [
			[
				'methods'         => 'POST',
				'callback'        => [$this, 'get_collects']
			]
		]);

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('rest_api_init', [$this, 'register_route_collects_count']);
		add_action('rest_api_init', [$this, 'register_route_collects']);

	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
