<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Async_Processing_Collections_Smart') ) {

  class Async_Processing_Collections_Smart extends Vendor_Background_Process {

		protected $action = 'wps_background_processing_collections_smart';

		protected $DB_Settings_Syncing;
		protected $DB_Collections_Smart;
		protected $WS;


		public function __construct($DB_Settings_Syncing, $DB_Collections_Smart, $WS) {

			$this->DB_Settings_Syncing				=	$DB_Settings_Syncing;
			$this->DB_Collections_Smart 			= $DB_Collections_Smart;
			$this->WS													=	$WS;

			parent::__construct();

		}


		/*

		Override this method to perform any actions required during the async request.

		*/
		protected function task($smart_collection) {

			// Stops background process if syncing stops
			if ( !$this->DB_Settings_Syncing->is_syncing() ) {
				$this->complete();
				return false;
			}

			// Actual work
			$result = $this->DB_Collections_Smart->insert_smart_collection($smart_collection);


			if (is_wp_error($result)) {
				$this->WS->save_notice_and_stop_sync($result);
				$this->complete();
				return false;
			}

			return false;

		}


		protected function after_queue_item_removal($smart_collection) {
			$this->DB_Settings_Syncing->increment_current_amount('smart_collections');
		}


		public function insert_smart_collections_batch($smart_collections) {

			foreach ($smart_collections as $key => $smart_collection) {
				$this->push_to_queue($smart_collection);
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
