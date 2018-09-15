<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Collections_Custom')) {

  class Collections_Custom extends \WPS\WS {

		protected $DB_Settings_Syncing;
		protected $DB_Settings_General;
		protected $DB_Collections_Custom;
		protected $CPT_Model;
		protected $Async_Processing_Collections_Custom;
		protected $Async_Processing_Posts_Collections_Custom;
		protected $HTTP;

  	public function __construct($DB_Settings_Syncing, $DB_Settings_General, $DB_Collections_Custom, $CPT_Model, $Async_Processing_Collections_Custom, $Async_Processing_Posts_Collections_Custom, $HTTP) {

			$this->DB_Settings_Syncing 												= $DB_Settings_Syncing;
			$this->DB_Settings_General 												= $DB_Settings_General;
			$this->DB_Collections_Custom											= $DB_Collections_Custom;
			$this->CPT_Model 																	= $CPT_Model;

			$this->Async_Processing_Collections_Custom 				= $Async_Processing_Collections_Custom;
			$this->Async_Processing_Posts_Collections_Custom 	= $Async_Processing_Posts_Collections_Custom;

			$this->HTTP 																			= $HTTP;

    }


		/*

		Get Custom Collections Count

		*/
		public function get_custom_collections_count() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error( Messages::get('nonce_invalid') . ' (get_custom_collections_count)' );
			}

			$collections = $this->HTTP->get('/admin/custom_collections/count.json');

			if ( is_wp_error($collections) ) {
				$this->DB_Settings_Syncing->save_notice_and_stop_sync($collections);
				$this->send_error( $collections->get_error_message() . ' (get_custom_collections_count)');
			}


			if (Utils::has($collections, 'count')) {
				$this->send_success(['custom_collections' => $collections->count]);

			} else {
				$this->send_warning( Messages::get('custom_collections_not_found') . ' (get_custom_collections_count)' );

			}

		}








		public function get_custom_collections_by_page($currentPage, $async = false) {
			return $this->HTTP->get("/admin/custom_collections.json", "?limit=" . $this->DB_Settings_General->get_items_per_request() . "&page=" . $currentPage, $async);
		}




		/*

		Get single collection

		*/
		public function get_single_collection() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error( Messages::get('nonce_invalid') . ' (get_single_collection)' );
			}


			$collections = $this->HTTP->get("/admin/custom_collections/" . $_POST['collectionID'] . ".json");


			if ( is_wp_error($collections) ) {
				$this->send_error($collections->get_error_message() . ' (get_single_collection)');
			}


			if (Utils::has($collections, 'custom_collection')) {
				$this->send_success($collections);

			} else {
				$this->send_warning( Messages::get('custom_collections_not_found') . ' (get_single_collection)' );
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

				$this->DB_Settings_Syncing->save_notice( Messages::get('missing_collections_for_page'), 'warning' );
				$this->send_success(); // Choosing not to end sync

			}

		}


		/*

		Hooks

		*/
		public function hooks() {

			add_action('wp_ajax_get_single_collection', [$this, 'get_single_collection']);
			add_action('wp_ajax_nopriv_get_single_collection', [$this, 'get_single_collection']);

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
