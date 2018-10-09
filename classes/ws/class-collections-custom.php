<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}

class Collections_Custom extends \WPS\WS {

	protected $DB_Settings_Syncing;
	protected $DB_Settings_General;
	protected $DB_Collections_Custom;
	protected $CPT_Model;
	protected $Async_Processing_Collections_Custom;
	protected $Async_Processing_Posts_Collections_Custom;
	protected $Shopify_API;

	public function __construct($DB_Settings_Syncing, $DB_Settings_General, $DB_Collections_Custom, $CPT_Model, $Async_Processing_Collections_Custom, $Async_Processing_Posts_Collections_Custom, $Shopify_API) {

		$this->DB_Settings_Syncing 												= $DB_Settings_Syncing;
		$this->DB_Settings_General 												= $DB_Settings_General;
		$this->DB_Collections_Custom											= $DB_Collections_Custom;
		$this->CPT_Model 																	= $CPT_Model;

		$this->Async_Processing_Collections_Custom 				= $Async_Processing_Collections_Custom;
		$this->Async_Processing_Posts_Collections_Custom 	= $Async_Processing_Posts_Collections_Custom;

		$this->Shopify_API 																= $Shopify_API;

	}


	/*

	Get Custom Collections Count

	*/
	public function get_custom_collections_count() {

		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (get_custom_collections_count)' );
		}

		// Gets custom collections count
		$collections_count = $this->Shopify_API->get_custom_collections_count();

		if ( is_wp_error($collections_count) ) {
			$this->DB_Settings_Syncing->save_notice_and_stop_sync($collections_count);
			$this->send_error( $collections_count->get_error_message() . ' (get_custom_collections_count)');
		}


		if (Utils::has($collections_count, 'count')) {
			$this->send_success(['custom_collections' => $collections_count->count]);

		} else {
			$this->send_warning( Messages::get('custom_collections_not_found') . ' (get_custom_collections_count)' );

		}

	}














	/*

	Get Custom Collections

	Runs for each "page" of the Shopify API

	*/
	public function get_bulk_custom_collections() {

		// First make sure nonce is valid
		if (!Utils::valid_backend_nonce($_POST['nonce'])) {
			$this->send_error( Messages::get('nonce_invalid') . ' (get_bulk_custom_collections)' );
		}

		$param_limit 					= $this->DB_Settings_General->get_items_per_request();
		$param_current_page 	= Utils::get_current_page($_POST);

		// Grab custom collections from Shopify
		$collections = $this->Shopify_API->get_custom_collections_per_page($param_limit, $param_current_page);

		if (is_wp_error($collections)) {
			$this->send_error($collections->get_error_message() . ' (get_bulk_custom_collections)');
		}

		// Fire off our async processing builds ...
		if (Utils::has($collections, 'custom_collections')) {

			$this->Async_Processing_Collections_Custom->insert_custom_collections_batch($collections->custom_collections);
			$this->Async_Processing_Posts_Collections_Custom->insert_posts_collections_custom_batch($collections->custom_collections);

			$this->send_success($collections->custom_collections);

		} else {

			$this->DB_Settings_Syncing->save_notice( Messages::get('missing_collections_for_page'), 'warning' );
			$this->send_success(); // Choosing not to end sync

		}

	}


	/*

	Hooks

	*/
	public function hooks() {

		add_action('wp_ajax_get_custom_collections_count', [$this, 'get_custom_collections_count']);
		add_action('wp_ajax_nopriv_get_custom_collections_count', [$this, 'get_custom_collections_count']);

		add_action('wp_ajax_get_bulk_custom_collections', [$this, 'get_bulk_custom_collections']);
		add_action('wp_ajax_nopriv_get_bulk_custom_collections', [$this, 'get_bulk_custom_collections']);

	}


	/*

	Init

	*/
	public function init() {
		$this->hooks();
	}


}
