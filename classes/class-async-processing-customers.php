<?php

namespace WPS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


if ( !class_exists('Async_Processing_Customers') ) {

  class Async_Processing_Customers extends Vendor_Background_Process {

		protected $action = 'wps_background_processing_customers';

		protected $DB_Settings_Syncing;
		protected $DB_Customers;
		protected $WS;


		public function __construct($DB_Settings_Syncing, $DB_Customers, $WS) {

			$this->DB_Settings_Syncing				=	$DB_Settings_Syncing;
			$this->DB_Customers 							= $DB_Customers;
			$this->WS 												= $WS;

			parent::__construct();

		}


		protected function task($customer) {

			// Stops background process if syncing stops
			if ( !$this->DB_Settings_Syncing->is_syncing() ) {
				$this->complete();
				return false;
			}


			$result = $this->DB_Customers->insert_customer($customer);


			if (is_wp_error($result)) {
				$this->WS->save_notice_and_stop_sync($result);
				$this->complete();
				return false;
			}

			// Need to return false to remove from queue
			return false;

		}


		protected function after_queue_item_removal($customer) {
			$this->DB_Settings_Syncing->increment_current_amount('customers');
		}


		public function insert_customers_batch($customers) {

			foreach ($customers as $customer) {
				$this->push_to_queue($customer);
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
