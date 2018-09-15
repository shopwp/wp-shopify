<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Async_Processing_Webhooks') ) {

  class Async_Processing_Webhooks extends Vendor_Background_Process {

		protected $action = 'wps_background_processing_webhooks';

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
		protected function task($topic) {

			// Stops background process if syncing stops
			if ( !$this->DB_Settings_Syncing->is_syncing() ) {
				$this->complete();
				return false;
			}

			// Actual work
			$result = $this->Webhooks->register_webhook( $topic, $this->Webhooks->get_callback_name_from_topic($topic) );

			if (is_wp_error($result)) {
				$this->DB_Settings_Syncing->save_notice_and_stop_sync($result);
				$this->complete();
				return false;
			}

			return false;

		}


		protected function after_queue_item_removal($topic) {
			$this->DB_Settings_Syncing->increment_current_amount('webhooks');
		}


		public function insert_webhooks_batch($webhooks) {

			if ( $this->DB_Settings_Syncing->max_packet_size_reached($webhooks) ) {
				$this->DB_Settings_Syncing->save_notice_and_stop_sync( $this->DB_Settings_Syncing->throw_max_allowed_packet() );
				$this->DB_Settings_Syncing->expire_sync();
				$this->complete();
			}

			foreach ($webhooks as $topic => $status_code) {
				$this->push_to_queue($topic);
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
