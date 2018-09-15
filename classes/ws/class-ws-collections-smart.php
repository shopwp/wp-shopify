<?php

namespace WPS\WS;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;
use WPS\Messages;


if (!class_exists('Collections_Smart')) {

  class Collections_Smart extends \WPS\WS {

		protected $DB_Settings_Syncing;
		protected $DB_Settings_General;
		protected $DB_Collections_Smart;
		protected $CPT_Model;
		protected $Async_Processing_Collections_Smart;
		protected $Async_Processing_Posts_Collections_Smart;
		protected $HTTP;

  	public function __construct($DB_Settings_Syncing, $DB_Settings_General, $DB_Collections_Smart, $CPT_Model, $Async_Processing_Collections_Smart, $Async_Processing_Posts_Collections_Smart, $HTTP) {

			$this->DB_Settings_Syncing 															= $DB_Settings_Syncing;
			$this->DB_Settings_General 															= $DB_Settings_General;
			$this->DB_Collections_Smart 														= $DB_Collections_Smart;
			$this->CPT_Model																				= $CPT_Model;

			$this->Async_Processing_Collections_Smart								= $Async_Processing_Collections_Smart;
			$this->Async_Processing_Posts_Collections_Smart					= $Async_Processing_Posts_Collections_Smart;

			$this->HTTP																							= $HTTP;

    }


		/*

		Get Smart Collections Count

		*/
		public function get_smart_collections_count() {

			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error( Messages::get('nonce_invalid') . ' (get_smart_collections_count)' );
			}

			$collections = $this->HTTP->get('/admin/smart_collections/count.json');

			if ( is_wp_error($collections) ) {

				$this->DB_Settings_Syncing->save_notice_and_stop_sync($collections);
				$this->send_error($collections->get_error_message() . ' (get_smart_collections_count)');
			}


			if (Utils::has($collections, 'count')) {
				$this->send_success(['smart_collections' => $collections->count]);

			} else {
				$this->send_warning( Messages::get('smart_collections_not_found') . ' (get_smart_collections_count)' );

			}


		}



		public function get_smart_collections_by_page($currentPage, $async = false) {
			return $this->HTTP->get("/admin/smart_collections.json", "?limit=" . $this->DB_Settings_General->get_items_per_request() . "&page=" . $currentPage, $async);
		}


		/*

	  Get Smart Collections

		Runs for each "page" of the Shopify API

	  */
		public function get_bulk_smart_collections() {

			// First make sure nonce is valid
			if (!Utils::valid_backend_nonce($_POST['nonce'])) {
				$this->send_error( Messages::get('nonce_invalid') . ' (get_bulk_smart_collections)' );
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

				$this->DB_Settings_Syncing->save_notice( Messages::get('missing_collections_for_page'), 'warning' );
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
