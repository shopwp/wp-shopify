<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\CPT as CPT_Main;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Collections_Custom')) {

  class Collections_Custom extends \WPS\WS {

		protected $DB_Settings_Syncing;
		protected $DB_Settings_General;
		protected $DB_Settings_Connection;

		protected $DB_Collections_Custom;
		protected $Messages;
		protected $CPT_Model;
		protected $WS;

  	public function __construct($DB_Settings_Syncing, $DB_Settings_General, $DB_Settings_Connection, $DB_Collections_Custom, $Messages, $Guzzle, $CPT_Model, $WS, $Async_Processing_Collections_Custom, $Async_Processing_Posts_Collections_Custom) {

			$this->DB_Settings_Syncing 												= $DB_Settings_Syncing;
			$this->DB_Settings_General 												= $DB_Settings_General;
			$this->DB_Settings_Connection 										= $DB_Settings_Connection;
			$this->DB_Collections_Custom											= $DB_Collections_Custom;
			$this->Messages 																	= $Messages;
			$this->CPT_Model 																	= $CPT_Model;
			$this->WS 																				= $WS;

			$this->Async_Processing_Collections_Custom 				= $Async_Processing_Collections_Custom;
			$this->Async_Processing_Posts_Collections_Custom 	= $Async_Processing_Posts_Collections_Custom;

			parent::__construct($Guzzle, $Messages, $DB_Settings_Connection, $DB_Settings_General, $DB_Settings_Syncing);

    }


		/*

		Delete Custom Collections

		*/
		public function delete_custom_collections() {

			$syncStates = $this->DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$this->DB_Collections_Custom->delete()) {
					return new \WP_Error('error', $this->Messages->message_delete_custom_collections_error . ' (delete_custom_collections)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['custom_collections']) {

					if (!$this->DB_Collections_Custom->delete()) {
						return new \WP_Error('error', $this->Messages->message_delete_custom_collections_error . ' (delete_custom_collections 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

		}


		/*

		Get Custom Collections Count

		*/
		public function get_custom_collections_count() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_custom_collections_count)');
			}

			$collections = $this->get('/admin/custom_collections/count.json');


			if ( is_wp_error($collections) ) {
				$this->WS->save_notice_and_stop_sync($collections);
				$this->send_error($collections->get_error_message() . ' (get_custom_collections_count)');
			}


			if (Utils::has($collections, 'count')) {
				$this->send_success(['custom_collections' => $collections->count]);

			} else {
				$this->send_warning($this->Messages->message_custom_collections_not_found . ' (get_custom_collections_count)');

			}

		}
























	/*

	Inserts Multiple Collections
	Only used during initial sync so we don't need to Insert
	Collects. Might need to change in future.

	*/
	public function insert_custom_collections($custom_collections) {

		// If no custom collections exist to insert, keep moving ...
		if (empty($custom_collections)) {
			return true;
		}

		$results = [];

		$custom_collections = Utils::flatten_collections_image_prop($custom_collections);

		$menu_order = CPT_Main::wps_find_latest_menu_order('collections');
		$all_collections = CPT_Main::get_all_posts_by_type(WPS_COLLECTIONS_POST_TYPE_SLUG);

		foreach ($custom_collections as $key => $custom_collection) {

			$this->DB_Settings_Syncing->die_if_not_syncing();

			// If product is published
			if (property_exists($custom_collection, 'published_at') && $custom_collection->published_at !== null) {

				$customPostTypeID = $this->CPT_Model->insert_or_update_collection($all_collections, $custom_collection, $menu_order);
				$custom_collection = $this->DB_Collections_Custom->assign_foreign_key($custom_collection, $customPostTypeID);
				$custom_collection = $this->DB_Collections_Custom->rename_primary_key($custom_collection, 'collection_id');

				$results[$customPostTypeID] = $this->DB_Collections_Custom->insert($custom_collection, 'custom_collection');

				$this->DB_Settings_Syncing->increment_current_amount('custom_collections');

			}

			$menu_order++;

		}

		return $results;

	}













		public function get_custom_collections_by_page($currentPage, $async = false) {
			return $this->get("/admin/custom_collections.json", "?limit=250&page=" . $currentPage, $async);
		}




		/*

		Get Collections

		*/
		public function get_custom_collections() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_custom_collections)');
			}


			if (!isset($_POST['currentPage']) || !$_POST['currentPage']) {
				$currentPage = 1;

			} else {
				$currentPage = $_POST['currentPage'];
			}


			$collections = $this->get_custom_collections_by_page($currentPage);

			if ( is_wp_error($collections) ) {
				$this->send_error($collections->get_error_message() . ' (get_custom_collections)');
			}


			if (Utils::has($collections, 'custom_collections')) {

				$results = $this->insert_custom_collections($collections->custom_collections);

				if (empty($results)) {
					$this->send_warning($this->Messages->message_insert_custom_collections_error . ' (get_custom_collections)');

				} else {
					$this->send_success($results);
				}

			} else {

				$this->send_warning($this->Messages->message_custom_collections_not_found . ' (get_custom_collections)');

			}


		}


		/*

		Get single collection

		*/
		public function get_single_collection() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_single_collection)');
			}


			$collections = $this->get("/admin/custom_collections/" . $_POST['collectionID'] . ".json");


			if ( is_wp_error($collections) ) {
				$this->send_error($collections->get_error_message() . ' (get_single_collection)');
			}


			if (Utils::has($collections, 'custom_collection')) {
				$this->send_success($collections);

			} else {
				$this->send_warning($this->Messages->message_custom_collections_not_found . ' (get_single_collection)');
			}


		}


		/*

		Get Custom Collections

		Runs for each "page" of the Shopify API (250 per page)

		*/
		public function get_bulk_custom_collections() {

			// First make sure nonce is valid
			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_bulk_custom_collections)');
			}

			// Grab smart collections from Shopify
			$collections = $this->get_custom_collections_by_page( Utils::get_current_page($_POST) );

			if (is_wp_error($collections)) {
				$this->send_error($collections->get_error_message() . ' (get_bulk_custom_collections)');
			}

			// Fire off our async processing builds ...
			if (Utils::has($collections, 'custom_collections')) {

				$this->Async_Processing_Collections_Custom->insert_custom_collections_batch($collections->custom_collections);
				$this->Async_Processing_Posts_Collections_Custom->insert_posts_collections_custom_batch($collections->custom_collections);

				$this->send_success($collections->custom_collections);

			} else {

				$this->DB_Settings_Syncing->save_notice($this->Messages->message_missing_collections_for_page, 'warning');
				$this->send_success(); // Choosing not to end sync

			}

		}


		/*

		Hooks

		*/
		public function hooks() {

			add_action('wp_ajax_get_single_collection', [$this, 'get_single_collection']);
			add_action('wp_ajax_nopriv_get_single_collection', [$this, 'get_single_collection']);

			add_action('wp_ajax_get_custom_collections', [$this, 'get_custom_collections']);
			add_action('wp_ajax_nopriv_get_custom_collections', [$this, 'get_custom_collections']);

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

}
