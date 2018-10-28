<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}


class Async_Processing_Webhooks_Deletions extends Vendor_Background_Process {

	protected $action = 'wps_background_processing_webhooks_deletions';

	protected $DB_Settings_Syncing;
	protected $Shopify_API;


	public function __construct($DB_Settings_Syncing, $Shopify_API) {

		$this->DB_Settings_Syncing				=	$DB_Settings_Syncing;
		$this->Shopify_API 								= $Shopify_API;

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
		$result = $this->Shopify_API->delete_webhook($webhook->id);


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

		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			$this->DB_Settings_Syncing->expire_sync();
		}

		$this->DB_Settings_Syncing->set_finished_webhooks_deletions(1);

		parent::complete();


	}

}
