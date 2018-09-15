<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Async_Processing_Options') ) {

  class Async_Processing_Options extends Vendor_Background_Process {

		protected $action = 'wps_background_processing_options';

		protected $DB_Settings_Syncing;
		protected $DB_Options;


		public function __construct($DB_Settings_Syncing, $DB_Options) {

			$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;
			$this->DB_Options 								= $DB_Options;

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
			$result = $this->DB_Options->insert_items_of_type($product->options);

			if (is_wp_error($result)) {
				$this->DB_Settings_Syncing->save_notice_and_stop_sync($result);
				$this->complete();
				return false;
			}

			return false;

		}


		protected function after_queue_item_removal($product) {
			$this->DB_Settings_Syncing->increment_current_amount('products');
		}


		public function insert_options_batch($products) {

			if ( $this->DB_Settings_Syncing->max_packet_size_reached($products) ) {
				$this->DB_Settings_Syncing->save_notice_and_stop_sync( $this->DB_Settings_Syncing->throw_max_allowed_packet() );
				$this->DB_Settings_Syncing->expire_sync();
				$this->complete();
			}

			foreach ($products as $product) {
				$this->push_to_queue($product);
			}

			$this->save()->dispatch();

		}


		protected function complete() {

			if (!$this->DB_Settings_Syncing->is_syncing() || $this->DB_Settings_Syncing->all_syncing_complete()) {
				$this->DB_Settings_Syncing->expire_sync();
			}

			parent::complete();

		}

  }

}
