<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Async_Processing_Images') ) {

  class Async_Processing_Images extends Vendor_Background_Process {

		protected $action = 'wps_background_processing_images';

		protected $DB_Settings_Syncing;
		protected $DB_Images;
		protected $WS;


		public function __construct($DB_Settings_Syncing, $DB_Images, $WS) {

			$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;
			$this->DB_Images 									= $DB_Images;
			$this->WS 												= $WS;

			parent::__construct();

		}


		/*

		Runs for each product

		*/
		protected function task($product) {

			// Stops background process if syncing stops
			if ( !$this->DB_Settings_Syncing->is_syncing() ) {
				$this->complete();
				return false;
			}

			// Actual work
			$result = $this->DB_Images->insert_image($product);


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


		/*

		Inserts all products -- runs a background process to prevent timeouts

		*/
		public function insert_images_batch($products) {

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
