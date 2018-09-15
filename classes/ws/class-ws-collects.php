<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Transients;
use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Collects')) {

  class Collects extends \WPS\WS {

		protected $DB_Collects;
		protected $DB_Settings_General;
		protected $DB_Settings_Syncing;
		protected $Async_Processing_Collects;
		protected $HTTP;

  	public function __construct($DB_Collects, $DB_Settings_General, $DB_Settings_Syncing, $Async_Processing_Collects, $HTTP) {

			$this->DB_Collects											= $DB_Collects;
			$this->DB_Settings_General							= $DB_Settings_General;
			$this->DB_Settings_Syncing							= $DB_Settings_Syncing;
			$this->Async_Processing_Collects				=	$Async_Processing_Collects;
			$this->HTTP															=	$HTTP;

    }



		public function get_collects_count_by_collection_id_endpoint($collection_id) {
			return '/admin/collects/count.json?collection_id=' . $collection_id;
		}


		/*

		Responsible for getting an array of API endpoints for given collection ids

		*/
		public function get_collects_count_urls_by_collection_ids() {

			$urls = [];
			$collection_ids = $this->DB_Settings_General->get_sync_by_collections_ids();

			foreach ($collection_ids as $collection_id) {
		    $urls[] = $this->get_collects_count_by_collection_id_endpoint($collection_id);
		  }

		  return $urls;

		}


		/*

		Responsible for calling the Shopify API multiple times based on count URLs

		*/
		public function get_total_counts_from_urls($urls) {

		  $products_count = [];

		  foreach ($urls as $url) {

		    $count = $this->HTTP->get($url);

		    if (!empty($count) && Utils::has($count, 'count')) {
					$products_count[] = $count->count;
				}

		  }

		  return array_sum($products_count);

		}




		/*

	  Get Collections Count

	  */
	  public function get_collects_count() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error( Messages::get('nonce_invalid') . ' (get_collects_count)' );
			}

			// User is syncing by collection
			if ($this->DB_Settings_General->is_syncing_by_collection()) {

				$urls = $this->get_collects_count_urls_by_collection_ids();
				$collects_count = $this->get_total_counts_from_urls($urls);

				$this->send_success(['collects' => $collects_count]);

			} else {

				$collects = $this->get_collects_total_count();

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



		public function get_collects_by_page($current_page) {
			return $this->HTTP->get("/admin/collects.json", "?limit=" . $this->DB_Settings_General->get_items_per_request() . "&page=" . $current_page);
		}

		public function get_collects_total_count() {
			return $this->HTTP->get('/admin/collects/count.json');
		}

		public function get_collects_by_product_id($product_id) {
			return $this->HTTP->get("/admin/collects.json", "?product_id=" . $product_id);
		}

		public function get_collects_from_collection_id($collection_id) {
			return $this->HTTP->get("/admin/collects.json", "?collection_id=" . $collection_id);
		}

		public function get_collects_by_collection_and_page($products_url_param) {
			return $this->HTTP->get("/admin/collects.json", $products_url_param);
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

		Runs for each "page" of the Shopify API

	  */
		public function get_bulk_collects() {

			// First make sure nonce is valid
			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error( Messages::get('nonce_invalid') . ' (get_bulk_collects)' );
			}

			// Check if user is syncing from collections -- returns proper products
			if ($this->DB_Settings_General->is_syncing_by_collection()) {

				$collection_ids = maybe_unserialize($this->DB_Settings_General->sync_by_collections());
				$collects_url_params = $this->get_endpoint_params_collection_id($collection_ids, Utils::get_current_page($_POST));

				$collects = $this->get_collects_by_collections_page($collects_url_params);
				$collects = Utils::flatten_array_into_object($collects, 'collects');


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

			$collects = $this->get_collects_by_product_id($item->id);

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

			$collects = $this->get_collects_from_collection_id($item->id);

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

}
