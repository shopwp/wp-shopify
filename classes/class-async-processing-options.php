<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Async_Processing_Options') ) {

  class Async_Processing_Options extends WP_Shopify_Background_Process {

		protected $action = 'wps_background_processing_options';

		protected $DB_Settings_Syncing;
		protected $DB_Options;
		protected $WS;


		public function __construct($DB_Settings_Syncing, $DB_Options, $WS) {

			$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;
			$this->DB_Options 								= $DB_Options;
			$this->WS 												= $WS;

			parent::__construct();

		}


		/*

		Override this method to perform any actions required during the async request.

		*/
		protected function task($product) {

			// Stops background process if syncing stops
			if ( !$this->DB_Settings_Syncing->is_syncing() ) {
				$this->complete();
				return false;
			}


			// Actual work
			$result = $this->DB_Options->insert_option($product);


			if (is_wp_error($result)) {
				$this->WS->save_notice_and_stop_sync($result);
				$this->complete();
				return false;
			}

			return false;

		}


		protected function after_queue_item_removal($product) {
			$this->DB_Settings_Syncing->increment_current_amount('products');
		}


		public function insert_options_batch($products) {

			foreach ($products as $product) {
				$this->push_to_queue($product);
			}

			$this->save()->dispatch();

		}


		protected function complete() {

			if (!$this->DB_Settings_Syncing->is_syncing() || $this->DB_Settings_Syncing->all_syncing_complete()) {
				$this->WS->expire_sync();
			}

			parent::complete();

		}

  }

}
