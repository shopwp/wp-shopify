<?php

namespace WPS\WS;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;
use WPS\CPT as CPT_Main;


if (!class_exists('Collections_Smart')) {

  class Collections_Smart extends \WPS\WS {

		protected $DB_Settings_Syncing;
		protected $DB_Settings_General;
		protected $DB_Settings_Connection;

		protected $DB_Collections_Smart;
		protected $Messages;
		protected $Guzzle;
		protected $CPT_Model;
		protected $WS;

  	public function __construct($DB_Settings_Syncing, $DB_Settings_General, $DB_Settings_Connection, $DB_Collections_Smart, $Messages, $Guzzle, $CPT_Model, $WS, $Async_Processing_Collections_Smart, $Async_Processing_Posts_Collections_Smart) {

			$this->DB_Settings_Syncing 															= $DB_Settings_Syncing;
			$this->DB_Settings_General 															= $DB_Settings_General;
			$this->DB_Settings_Connection 													= $DB_Settings_Connection;
			$this->DB_Collections_Smart 														= $DB_Collections_Smart;
			$this->Messages 																				= $Messages;
			$this->CPT_Model																				= $CPT_Model;
			$this->WS																								= $WS;

			$this->Async_Processing_Collections_Smart								= $Async_Processing_Collections_Smart;
			$this->Async_Processing_Posts_Collections_Smart					= $Async_Processing_Posts_Collections_Smart;


			/*

			$this->Guzzle
			$this->Messages
			$this->DB_Settings_Connection
			$this->DB_Settings_General

			*/
			parent::__construct($Guzzle, $Messages, $DB_Settings_Connection, $DB_Settings_General, $DB_Settings_Syncing);

    }


		/*

		Delete Smart Collections

		*/
		public function delete_smart_collections() {

			$syncStates = $this->DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$this->DB_Collections_Smart->delete()) {
					return new \WP_Error('error', $this->Messages->message_delete_smart_collections_error . ' (delete_smart_collections)');

				} else {
					return true;
				}

			} else {

				if ($syncStates['smart_collections']) {

					if (!$this->DB_Collections_Smart->delete()) {
						return new \WP_Error('error', $this->Messages->message_delete_smart_collections_error . ' (delete_smart_collections 2)');

					} else {
						return true;
					}

				} else {
					return true;
				}

			}

		}


		/*

		Get Smart Collections Count

		*/
		public function get_smart_collections_count() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_smart_collections_count)');
			}

			$collections = $this->get('/admin/smart_collections/count.json');

			if ( is_wp_error($collections) ) {

				$this->WS->save_notice_and_stop_sync($collections);
				$this->send_error($collections->get_error_message() . ' (get_smart_collections_count)');
			}


			if (Utils::has($collections, 'count')) {
				$this->send_success(['smart_collections' => $collections->count]);

			} else {
				$this->send_warning($this->Messages->message_smart_collections_not_found . ' (get_smart_collections_count)');

			}


		}

















		/*

		Insert an array of Smart Collections

		We currently don't need to insert Collects here because the
		only time we're calling this function is during initial sync
		which calls Collects for us. We _may_ run into issues in the future.

		TODO: Revist

		*/
		public function insert_smart_collections($smart_collections) {

			// If no smart collections exist to insert, keep moving ...
			if (empty($smart_collections)) {
				return true;
			}

			$results = [];

			$smart_collections = Utils::flatten_collections_image_prop($smart_collections);
			$menu_order = CPT_Main::wps_find_latest_menu_order('collections');
			$all_collections = CPT_Main::get_all_posts_by_type(WPS_COLLECTIONS_POST_TYPE_SLUG);

			foreach ($smart_collections as $key => $smart_collection) {

				$this->DB_Settings_Syncing->die_if_not_syncing();

				if (!$this->collection_was_deleted($smart_collection)) {

					$customPostTypeID = $this->CPT_Model->insert_or_update_collection($all_collections, $smart_collection, $menu_order);

					$smart_collection = $this->DB_Collections_Smart->assign_foreign_key($smart_collection, $customPostTypeID);
					$smart_collection = $this->DB_Collections_Smart->rename_primary_key($smart_collection);

					$results[$customPostTypeID] = $this->DB_Collections_Smart->insert($smart_collection, 'smart_collection');

				}

				$menu_order++;

			}

			return $results;

		}





































		public function get_smart_collections_by_page($currentPage, $async = false) {
			return $this->get("/admin/smart_collections.json", "?limit=250&page=" . $currentPage, $async);
		}






		/*

		Get Collections

		TODO: Decouple the get and insert

		*/
		public function get_smart_collections() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_smart_collections)');
			}

			// if (Utils::emptyConnection($connection)) {
			// 	$this->send_error($this->Messages->message_connection_not_found . ' (get_smart_collections)');
			// }
			//
			// if (!$this->DB_Settings_Syncing->is_syncing()) {
			// 	$this->send_error($this->Messages->message_connection_not_syncing . ' (get_smart_collections)');
			// }


			if (!isset($_POST['currentPage']) || !$_POST['currentPage']) {
				$currentPage = 1;

			} else {
				$currentPage = $_POST['currentPage'];
			}


			$collections = $this->get_smart_collections_by_page($currentPage);


			if ( is_wp_error($collections) ) {
				$this->send_error($collections->get_error_message() . ' (get_smart_collections_by_page)');
			}

			if (Utils::has($collections, 'smart_collections')) {

				$results = $this->insert_smart_collections($collections->smart_collections);

				if (empty($results)) {
					$this->send_error($this->Messages->message_insert_smart_collections_error . ' (get_smart_collections)');

				} else {
					$this->send_success($results);
				}

			} else {

				$this->send_warning($this->Messages->message_smart_collections_not_found . ' (get_smart_collections)');

			}


		}


		/*

	  Get Smart Collections

		Runs for each "page" of the Shopify API (250 per page)

	  */
		public function get_bulk_smart_collections() {

			// First make sure nonce is valid
			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_bulk_smart_collections)');
			}

			// Grab smart collections from Shopify
			$collections = $this->get_smart_collections_by_page( Utils::get_current_page($_POST) );

			// Check if error occured during request
			if (is_wp_error($collections)) {
				$this->send_error($collections->get_error_message() . ' (get_bulk_smart_collections)');
			}

			// Fire off our async processing builds ...
			if (Utils::has($collections, 'smart_collections')) {

				$this->Async_Processing_Collections_Smart->insert_smart_collections_batch($collections->smart_collections);
				$this->Async_Processing_Posts_Collections_Smart->insert_posts_collections_smart_batch($collections->smart_collections);

				$this->send_success($collections->smart_collections);

			} else {

				$this->DB_Settings_Syncing->save_notice($this->Messages->message_missing_collections_for_page, 'warning');
				$this->send_success(); // Choosing not to end sync

			}

		}






		public function only_existing_collections($collections) {

			$collections_unserialized = maybe_unserialize($collections);
			return array_filter($collections_unserialized, [__CLASS__, 'smart_collection_exists']);

		}



		public function smart_collection_exists($collection_id) {

			if ($this->DB_Collections_Smart->get($collection_id)) {
				return $collection_id;
			}

		}








		/*

		Hooks

		*/
		public function hooks() {

			add_action('wp_ajax_get_smart_collections', [$this, 'get_smart_collections']);
			add_action('wp_ajax_nopriv_get_smart_collections', [$this, 'get_smart_collections']);

			add_action('wp_ajax_get_smart_collections_count', [$this, 'get_smart_collections_count']);
			add_action('wp_ajax_nopriv_get_smart_collections_count', [$this, 'get_smart_collections_count']);

			add_action('wp_ajax_get_bulk_smart_collections', [$this, 'get_bulk_smart_collections']);
			add_action('wp_ajax_nopriv_get_bulk_smart_collections', [$this, 'get_bulk_smart_collections']);

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}


  }

}
