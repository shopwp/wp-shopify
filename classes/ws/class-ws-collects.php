<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Transients;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Collects')) {

  class Collects extends \WPS\WS {

		protected $WS;

  	public function __construct($DB_Collects, $DB_Settings_General, $DB_Settings_Connection, $DB_Settings_Syncing, $WS, $Messages, $Guzzle, $Async_Processing_Collects) {

			$this->DB_Collects											= $DB_Collects;
			$this->DB_Settings_General							= $DB_Settings_General;
			$this->DB_Settings_Connection						= $DB_Settings_Connection;
			$this->DB_Settings_Syncing							= $DB_Settings_Syncing;
			$this->WS																= $WS;
			$this->Messages													= $Messages;
			$this->Async_Processing_Collects				=	$Async_Processing_Collects;

			parent::__construct($Guzzle, $Messages, $DB_Settings_Connection, $DB_Settings_General, $DB_Settings_Syncing);

    }


		/*

	  delete_collects

	  */
	  public function delete_collects() {

			$syncStates = $this->DB_Settings_General->selective_sync_status();

			if ($syncStates['all']) {

				if (!$this->DB_Collects->delete()) {
		      return new \WP_Error('error', $this->Messages->message_delete_collects_error . ' (delete_collects)');

		    } else {
		      return true;
		    }

			} else {

				if ($syncStates['products']) {

					if (!$this->DB_Collects->delete()) {
			      return new \WP_Error('error', $this->Messages->message_delete_collects_error . ' (delete_collects 2)');

			    } else {
			      return true;
			    }

				} else {
					return true;
				}

			}

	  }


		/*

	  Get Collections Count

	  */
	  public function get_collects_count() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_collects_count)');
			}

			// User is syncing by collection
			if ($this->DB_Settings_General->is_syncing_by_collection()) {

				$urls = $this->construct_sync_by_collections_count_url('collects');
				$collects_count = $this->get_counts_from_urls($urls);

				$this->send_success(['collects' => $collects_count]);

			} else {

				$collects = $this->get('/admin/collects/count.json');

				if ( is_wp_error($collects) ) {
					$this->WS->save_notice_and_stop_sync($collects);
					$this->send_error($collects->get_error_message() . ' (get_collects_count)');
				}


				if (Utils::has($collects, 'count')) {
					$this->send_success(['collects' => $collects->count]);

				} else {
					$this->send_warning($this->Messages->message_collects_not_found . ' (get_collects_count)');

				}

			}


	  }


		/*

		Gets products by page

		*/
		public function get_collects_by_page($currentPage) {
			return $this->get("/admin/collects.json", "?limit=250&page=" . $currentPage);
		}





















		public function get_collects_by_collection_and_page($products_url_param) {
			return $this->get("/admin/collects.json", $products_url_param);
		}


		public function get_collects_by_collections_page($collects_url_params) {

			$collects = [];

			foreach ($collects_url_params as $product_url_param) {

				$result = $this->get_collects_by_collection_and_page($product_url_param)->collects;

				if (is_wp_error($result)) {
					return $result;

				} else {
					$collects[] = $result;
				}

			}

			return $collects;

		}






		/*

	  Get Bulk Collects

		Runs for each "page" of the Shopify API (250 per page)

	  */
		public function get_bulk_collects() {

			// First make sure nonce is valid
			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_bulk_collects)');
			}

			// Check if user is syncing from collections -- returns proper products
			if ($this->DB_Settings_General->is_syncing_by_collection()) {

				$collection_ids = maybe_unserialize($this->DB_Settings_General->sync_by_collections());
				$collects_url_params = $this->construct_sync_by_collections_api_urls($collection_ids, Utils::get_current_page($_POST));

				$collects = $this->get_collects_by_collections_page($collects_url_params);
				$collects = $this->flatten_data_from_sync_by_collections($collects, 'collects');


			} else {
				$collects = $this->get_collects_by_page( Utils::get_current_page($_POST) );
			}

			// Check if error occured during request
			if (is_wp_error($collects)) {
				$this->send_error($collects->get_error_message() . ' (get_bulk_collects)');
			}


			// Fire off our async processing builds ...
			if (Utils::has($collects, 'collects')) {

				$this->Async_Processing_Collects->insert_collects_batch($collects->collects);

				$this->send_success($collects->collects);

			} else {

				// This page of collects was empty, show warning to user
				$this->DB_Settings_Syncing->save_notice($this->Messages->message_missing_collects_for_page, 'warning');
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

		Get a list of collects by product ID

		*/
		public function get_collects() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error($this->Messages->message_nonce_invalid . ' (get_collects)');
			}

			if (!$this->DB_Settings_Syncing->is_syncing()) {
				$this->send_error($this->Messages->message_connection_not_syncing . ' (get_collects)');
			}


			if (!isset($_POST['currentPage']) || !$_POST['currentPage']) {
				$currentPage = 1;

			} else {
				$currentPage = $_POST['currentPage'];
			}


			$collects = $this->get("/admin/collects.json", "?limit=250&page=" . $currentPage);

			if ( is_wp_error($collects) ) {
				$this->send_error($collects->get_error_message() . ' (get_collects)');
			}


			if (Utils::has($collects, 'collects')) {

				$resultCollects = $this->DB_Collects->insert_collects($collects->collects);

				if (empty($resultCollects)) {
					$this->send_error($this->Messages->message_insert_collects_error . ' (get_collects)');

				} else {
					$this->send_success($resultCollects);

				}

			} else {
				$this->send_warning($this->Messages->message_insert_collects_error_missing . ' (get_collects)');

			}

		}





































		/*

		Update Collects

		*/
		public function update_collects_from_product($product) {

			if (Utils::has($product, 'id')) {
				$product_id = $product->id;

			} else {
				$product_id = $product->product_id;
			}


			$results = [];
			$collects_from_shopify = $this->get_collects_from_product($product_id);

			if (!$collects_from_shopify) {
				return false;
			}


			/*

			In order to handle image creation / deletions, we need to compare what's
			currently in the database with what gets sent back via the
			product/update webhook.

			*/
			$current_collects_for_product = $this->DB_Collects->get_rows('product_id', $product_id);
			$current_collects_for_product_array = Utils::convert_object_to_array($current_collects_for_product);
			$collects_from_shopify = Utils::convert_object_to_array($collects_from_shopify->collects);

			$collects_to_add = Utils::wps_find_items_to_add($current_collects_for_product_array, $collects_from_shopify, true);
			$collects_to_delete = Utils::wps_find_items_to_delete($current_collects_for_product_array, $collects_from_shopify, true);

			// Collects to add ...
			if (count($collects_to_add) > 0) {
				$this->DB_Collects->insert_collects($collects_to_add);
			}

			// Collects to delete ...
			if (count($collects_to_delete) > 0) {
				$this->DB_Collects->delete_collects($collects_to_delete);
			}

			return $results;

		}


		/*

		Update Collects from collection ID

		*/
		public function update_collects_from_collection_id($collection_id) {

			// Collects from Plugin
			$results = [];
			$current_collection_collects = $this->DB_Collects->get_rows('collection_id', $collection_id);
			$new_collection_collects = $this->get_collects_from_collection($collection_id);

			// Responsible for updating Collects associated with the Collection
			if (Utils::array_not_empty($new_collection_collects)) {

				$collectsToAdd = Utils::wps_find_items_to_add($current_collection_collects, $new_collection_collects, true);
				$collectsToDelete = Utils::wps_find_items_to_delete($current_collection_collects, $new_collection_collects, true);


				// Collects to add ...
				if (count($collectsToAdd) > 0) {
					$results['collects_created'][] = $this->DB_Collects->insert_collects($collectsToAdd);
				}

				// Collects to delete ...
				if (count($collectsToDelete) > 0) {
					$results['collects_deleted'][] = $this->DB_Collects->delete_collects($collectsToDelete);
				}


			}

			return $results;

		}













































		/*

		Get a list of collects by product ID

		*/
		public function get_collects_from_product($productID = null) {

			if ($productID === null) {
				return false;
			}

			$collects = $this->get("/admin/collects.json", "?product_id=" . $productID);

			if ( is_wp_error($collects) ) {
				return false;
			}

			if (Utils::has($collects, 'collects')) {
				return $collects;

			} else {
				return false;
			}


		}


		/*

		Get a list of collects by collection ID

		*/
		public function get_collects_from_collection($collectionID = null) {

			$ajax = true;

			if ($collectionID === null) {
				$collectionID = $_POST['collectionID'];

			} else {
				$ajax = false;
			}


			if ($ajax) {

				if (!Utils::valid_backend_nonce($_POST['nonce'])) {
					$this->send_error($this->Messages->message_nonce_invalid . ' (get_collects_from_collection)');
				}

			}


			$collects = $this->get("/admin/collects.json", "?collection_id=" . $collectionID);

			if ( is_wp_error($collects) ) {
				$data = $this->get_error_message($collects) . ' (get_collects_from_collection)';
			}

			if (Utils::has($collects, 'collects')) {
				$data = $collects->collects;
			}

			if ($ajax) {

				$this->send_success($data);

			} else {
				return $data;

			}


		}


		/*

		Hooks

		*/
		public function hooks() {

			add_action('wp_ajax_get_collects_from_collection', [$this, 'get_collects_from_collection'] );
			add_action('wp_ajax_nopriv_get_collects_from_collection', [$this, 'get_collects_from_collection'] );

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

}
