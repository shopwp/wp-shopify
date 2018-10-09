<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Transients;
use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}

class Collects extends \WPS\WS {

	protected $DB_Settings_General;
	protected $DB_Settings_Syncing;
	protected $Async_Processing_Collects;
	protected $Shopify_API;


	public function __construct($DB_Settings_General, $DB_Settings_Syncing, $Async_Processing_Collects, $Shopify_API) {

		$this->DB_Settings_General							= $DB_Settings_General;
		$this->DB_Settings_Syncing							= $DB_Settings_Syncing;
		$this->Async_Processing_Collects				=	$Async_Processing_Collects;
		$this->Shopify_API											=	$Shopify_API;

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

		return array_sum($collects_count);

	}


	/*

	Get Collections Count

	*/
	public function get_collects_count() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (get_collects_count)' );
		}

		// User is syncing by collection
		if ( $this->DB_Settings_General->is_syncing_by_collection() ) {

			$collects_count = $this->get_collects_count_by_collection_ids();

			$this->send_success(['collects' => $collects_count]);

		} else {

			$collects = $this->Shopify_API->get_collects_count();

			if ( is_wp_error($collects) ) {
				$this->DB_Settings_Syncing->save_notice_and_stop_sync($collects);
				$this->send_error($collects->get_error_message() . ' (get_collects_count)');
			}


			if (Utils::has($collects, 'count')) {
				$this->send_success(['collects' => $collects->count]);

			} else {
				$this->send_warning( Messages::get('collects_not_found') . ' (get_collects_count)' );

			}

		}


	}



	public function get_collects_per_page($current_page) {

		$param_limit = $this->DB_Settings_General->get_items_per_request();

		return $this->Shopify_API->get_collects_per_page($param_limit, $current_page);

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
			$result = $this->normalize_collects_response($result);

			if (is_wp_error($result)) {
				return $result;
			}

			$collects = array_merge($collects, $result);

		}

		return $collects;

	}






	/*

	Get Bulk Collects

	Runs for each "page" of the Shopify API

	*/
	public function get_bulk_collects() {

		// First make sure nonce is valid
		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (get_bulk_collects)' );
		}

		$current_page = Utils::get_current_page($_POST);

		// Check if user is syncing from collections -- returns proper products
		if ( $this->DB_Settings_General->is_syncing_by_collection() ) {
			$collects = $this->get_collects_from_collections( $current_page );

		} else {
			$collects = $this->get_collects_per_page( $current_page );
		}


		// Check if error occured during request
		if (is_wp_error($collects)) {
			$this->send_error($collects->get_error_message() . ' (get_bulk_collects)');
		}


		$collects = $this->normalize_collects_response($collects);


		// Fire off our async processing builds ...
		if ( !empty($collects) ) {

			$this->Async_Processing_Collects->insert_collects_batch($collects);
			$this->send_success($collects);

		} else {

			// This page of collects was empty, show warning to user
			$this->DB_Settings_Syncing->save_notice( Messages::get('missing_collects_for_page'), 'warning' );
			$this->send_success();

		}


	}


	/*

	Saves collects queue count

	*/
	public function insert_collects_queue_count() {
		$this->send_success( Transients::set('wps_async_processing_collects_queue_count', $_POST['queueCount']) );
	}


	/*

	Responsible for adding collects to $data

	*/
	public function add_collects_to_item($item, $collects) {

		$item->collects = $collects;

		return $item;

	}


	/*

	Get a list of collects by product ID

	*/
	public function get_collects_from_product($item = null) {

		$collects = $this->Shopify_API->get_collects_by_product_id($item->id);

		if ( is_wp_error($collects) || Utils::object_is_empty($collects) ) {
			$collects_to_add = [];

		} else {
			$collects_to_add = $collects->collects;
		}

		return $this->add_collects_to_item($item, $collects_to_add);

	}


	/*

	Get a list of collects by collection ID

	*/
	public function get_collects_from_collection($item) {

		$collects = $this->Shopify_API->get_collects_from_collection_id($item->id);

		if ( is_wp_error($collects) || Utils::object_is_empty($collects) ) {
			$collects_to_add = [];

		} else {
			$collects_to_add = $collects->collects;
		}

		return $this->add_collects_to_item($item, $collects_to_add);

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('wp_ajax_insert_collects_queue_count', [$this, 'insert_collects_queue_count']);
		add_action('wp_ajax_nopriv_insert_collects_queue_count', [$this, 'insert_collects_queue_count']);

		add_action('wp_ajax_get_collects_count', [$this, 'get_collects_count']);
		add_action('wp_ajax_nopriv_get_collects_count', [$this, 'get_collects_count']);

		add_action('wp_ajax_get_bulk_collects', [$this, 'get_bulk_collects']);
		add_action('wp_ajax_nopriv_get_bulk_collects', [$this, 'get_bulk_collects']);

	}


	/*

	Init

	*/
	public function init() {
		$this->hooks();
	}


}
