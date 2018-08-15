<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Async_Processing_Collects') ) {

  class Async_Processing_Collects extends WP_Shopify_Background_Process {

		protected $action = 'wps_background_processing_collects';

		protected $DB_Settings_Syncing;
		protected $DB_Collects;
		protected $WS;

		public function __construct($DB_Settings_Syncing, $DB_Collects, $WS) {

			$this->DB_Settings_Syncing				=	$DB_Settings_Syncing;
			$this->DB_Collects 								= $DB_Collects;
			$this->WS 												= $WS;

			parent::__construct();

		}


		/*

		Override this method to perform any actions required during the async request.

		*/
		protected function task($collect) {

			// Stops background process if syncing stops
			if ( !$this->DB_Settings_Syncing->is_syncing() ) {
				$this->complete();
				return false;
			}

			// Actual work
			$result = $this->DB_Collects->insert($collect, 'collect');

			if (is_wp_error($result)) {
				$this->WS->save_notice_and_stop_sync($result);
				$this->complete();
				return false;
			}

			return false;

		}


		public function insert_collects_batch($collects) {

			foreach ($collects as $collect) {
				$this->push_to_queue($collect);
			}

			$this->save()->dispatch();

		}


		protected function after_queue_item_removal($collect) {
			$this->DB_Settings_Syncing->increment_current_amount('collects');
		}


		protected function complete() {

			if (!$this->DB_Settings_Syncing->is_syncing() || $this->DB_Settings_Syncing->all_syncing_complete()) {
				$this->WS->expire_sync();
			}

			parent::complete();

		}

  }

}
