<?php

namespace WPS\Processing;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;
use WPS\Utils\Server;


class Customers extends \WPS\Processing\Vendor_Background_Process {

	protected $action = 'wps_background_processing_customers';

	protected $DB_Settings_Syncing;
	protected $DB_Customers;


	public function __construct($DB_Settings_Syncing, $DB_Customers) {

		$this->DB_Settings_Syncing				=	$DB_Settings_Syncing;
		$this->DB_Customers 							= $DB_Customers;

		parent::__construct($DB_Settings_Syncing);

	}


	/*

	Entry point. Initial call before processing starts.

	*/
	public function process($items, $params = false) {

		if ( $this->expired_from_server_issues($items, __METHOD__, __LINE__) ) {
			return;
		}

		$this->dispatch_items($items);

	}


	/*

	Performs actions required for each item in the queue

	*/
	protected function task($customer) {

		// Stops background process if syncing stops
		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			$this->complete();
			return false;
		}

		// Actual work
		$result = $this->DB_Customers->insert_items_of_type($customer);

		// Save warnings if exist ...
		$this->DB_Settings_Syncing->maybe_save_warning_from_insert($result, 'Customer', $customer->id);


		if (is_wp_error($result)) {
			$this->DB_Settings_Syncing->save_notice_and_expire_sync($result);
			$this->complete();
			return false;
		}

		// Need to return false to remove from queue
		return false;

	}


	/*

	After an individual task item is removed from the queue

	*/
	protected function after_queue_item_removal($customer) {
		$this->DB_Settings_Syncing->increment_current_amount('customers');
	}


}
