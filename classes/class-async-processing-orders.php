<?php

namespace WPS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Async_Processing_Orders') ) {

  class Async_Processing_Orders extends WP_Shopify_Background_Process {

		protected $action = 'wps_background_processing_orders';

		protected $DB_Settings_Syncing;
		protected $DB_Orders;
		protected $WS;

		public function __construct($DB_Settings_Syncing, $DB_Orders, $WS) {

			$this->DB_Settings_Syncing				=	$DB_Settings_Syncing;
			$this->DB_Orders 									= $DB_Orders;
			$this->WS													= $WS;

			parent::__construct();

		}


		/*

		Override this method to perform any actions required during the async request.

		*/
		protected function task($order) {

			// Stops background process if syncing stops
			if ( !$this->DB_Settings_Syncing->is_syncing() ) {
				$this->complete();
				return false;
			}

			// Actual work
			$result = $this->DB_Orders->insert( Utils::convert_to_assoc_array($order), 'order');


			if (is_wp_error($result)) {
				$this->WS->save_notice_and_stop_sync($result);
				$this->complete();
				return false;
			}

			return false;

		}


		protected function after_queue_item_removal($order) {
			$this->DB_Settings_Syncing->increment_current_amount('orders');
		}


		public function insert_orders_batch($orders) {

			foreach ($orders as $order) {
				$this->push_to_queue($order);
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
