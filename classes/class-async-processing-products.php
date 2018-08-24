<?php

namespace WPS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Async_Processing_Products') ) {

  class Async_Processing_Products extends Vendor_Background_Process {

		protected $action = 'wps_background_processing_products';

		protected $DB_Settings_Syncing;
		protected $DB_Products;
		protected $WS;
		protected $compatible_charset;

		public function __construct($DB_Settings_Syncing, $DB_Products, $WS) {

			$this->DB 												= $DB_Settings_Syncing; // used only for readability
			$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;
			$this->DB_Products 								= $DB_Products;
			$this->WS 												= $WS;

			$this->compatible_charsets				= true;

			parent::__construct();

		}


		protected function task($product) {

			// Stops background process if syncing stops
			if ( !$this->DB_Settings_Syncing->is_syncing() ) {
				$this->complete();
				return false;
			}

			// Actual work
			$result = $this->DB_Products->insert_product($product);
			

			if (is_wp_error($result)) {
				$this->WS->save_notice_and_stop_sync($result);
				$this->complete();
				return false;
			}

			return false;

		}


		/*

		Used to increment the syncing current amounts

		*/
		protected function after_queue_item_removal($product) {
			$this->DB_Settings_Syncing->increment_current_amount('products');
		}





		protected function before_queue_item_save($items) {

			if ($this->DB->has_compatible_charsets([WPS_TABLE_NAME_WP_OPTIONS, WPS_TABLE_NAME_PRODUCTS])) {
				return $items;
			}

			$items_serialized_encoded = $this->DB->encode_data( json_encode($items) );

			return json_decode($items_serialized_encoded);

		}


		/*

		Called from the frontend to begin the syncing batch insert

		*/
		public function insert_products_batch($products) {

			$products_filtered = Utils::filter_data_by($products, ['variants', 'options']);

			foreach ($products_filtered as $product) {
				$this->push_to_queue($product);
			}

			$this->save()->dispatch();

		}


		/*

		Called when the syncing completes

		*/
		protected function complete() {

			if (!$this->DB_Settings_Syncing->is_syncing() || $this->DB_Settings_Syncing->all_syncing_complete()) {
				$this->WS->expire_sync();
			}

			parent::complete();

		}


  }

}
