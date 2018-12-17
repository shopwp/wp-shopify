<?php

namespace WPS\Processing;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;
use WPS\Utils\Server;


class Webhooks_Deletions extends \WPS\Processing\Vendor_Background_Process {

	protected $action = 'wps_background_processing_webhooks_deletions';

	protected $DB_Settings_Syncing;
	protected $Shopify_API;


	public function __construct($DB_Settings_Syncing, $Shopify_API) {

		$this->DB_Settings_Syncing				=	$DB_Settings_Syncing;
		$this->Shopify_API 								= $Shopify_API;

		parent::__construct($DB_Settings_Syncing);

	}


	/*

	Entry point. Initial call before processing starts.

	*/
	public function process($items, $params = false) {

		if ( $this->expired_from_server_issues($items, __METHOD__, __LINE__) ) {
			return;
		}

		$this->DB_Settings_Syncing->set_finished_webhooks_deletions(0);

		$this->dispatch_items($items);

	}


	/*

	Performs actions required for each item in the queue

	*/
	protected function task($webhook) {

		// Stops background process if syncing stops
		if ( !$this->DB_Settings_Syncing->is_syncing() || empty($webhook) ) {
			$this->complete();
			return false;
		}

		// Actual work
		$result = $this->Shopify_API->delete_webhook($webhook->id);


		if (is_wp_error($result)) {
			$this->DB_Settings_Syncing->save_notice_and_expire_sync($result);
			$this->complete();
			return false;
		}

		return false;

	}


	/*

	Complete can be called via both error and success. Therefore, we need
	to check is_syncing() to ensure predictable expiration.

	*/
	protected function complete() {

		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			$this->DB_Settings_Syncing->expire_sync();
		}

		$this->DB_Settings_Syncing->set_finished_webhooks_deletions(1);

		parent::complete();


	}

}
