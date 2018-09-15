<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Async_Processing_Webhooks_Deletions') ) {

  class Async_Processing_Webhooks_Deletions extends Vendor_Background_Process {

		protected $action = 'wps_background_processing_webhooks_deletions';

		protected $DB_Settings_Syncing;
		protected $Webhooks;


		public function __construct($DB_Settings_Syncing, $Webhooks) {

			$this->DB_Settings_Syncing				=	$DB_Settings_Syncing;
			$this->Webhooks 									= $Webhooks;

			parent::__construct();

		}


		/*

		Override this method to perform any actions required during the async request.

		*/
		protected function task($webhook) {

			// Stops background process if syncing stops
			if ( !$this->DB_Settings_Syncing->is_syncing() ) {
				$this->complete();
				return false;
			}

			// Actual work
			$result = $this->Webhooks->delete_webhook($webhook);

			if (is_wp_error($result)) {
				$this->DB_Settings_Syncing->save_notice_and_stop_sync($result);
				$this->complete();
				return false;
			}

			return false;

		}


		public function delete_webhooks_batch($webhooks) {

			if ( $this->DB_Settings_Syncing->max_packet_size_reached($webhooks) ) {

				$this->DB_Settings_Syncing->save_notice_and_stop_sync( $this->DB_Settings_Syncing->throw_max_allowed_packet() );
				$this->DB_Settings_Syncing->expire_sync();
				$this->complete();

			}


			foreach ($webhooks as $webhook) {
				$this->push_to_queue($webhook);
			}

			$this->save()->dispatch();

		}


		protected function complete() {

			if (!$this->DB_Settings_Syncing->is_syncing() || $this->DB_Settings_Syncing->all_syncing_complete()) {
				$this->DB_Settings_Syncing->expire_sync();
			}

			$this->DB_Settings_Syncing->set_finished_webhooks_deletions(1);

			parent::complete();


		}

  }

}
