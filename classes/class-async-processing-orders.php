<?php

namespace WPS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class Async_Processing_Orders extends Vendor_Background_Process {

	protected $action = 'wps_background_processing_orders';

	protected $DB_Settings_Syncing;
	protected $DB_Orders;

	public function __construct($DB_Settings_Syncing, $DB_Orders) {

		$this->DB_Settings_Syncing				=	$DB_Settings_Syncing;
		$this->DB_Orders 									= $DB_Orders;

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
		$result = $this->DB_Orders->insert_items_of_type( $this->DB_Orders->mod_before_change($order) );


		// Save warnings if exist ...
		$this->DB_Settings_Syncing->maybe_save_warning_from_insert($result, 'Order', $order->id);


		if (is_wp_error($result)) {
			$this->DB_Settings_Syncing->save_notice_and_stop_sync($result);
			$this->complete();
			return false;
		}

		return false;

	}


	protected function after_queue_item_removal($order) {
		$this->DB_Settings_Syncing->increment_current_amount('orders');
	}


	public function insert_orders_batch($orders) {

		if ( $this->DB_Orders->max_packet_size_reached($orders) ) {

			$this->DB_Settings_Syncing->save_notice_and_stop_sync( $this->DB_Settings_Syncing->throw_max_allowed_packet() );
			$this->DB_Settings_Syncing->expire_sync();
			$this->complete();

		}

		foreach ($orders as $order) {
			$this->push_to_queue($order);
		}

		$this->save()->dispatch();

	}


	protected function complete() {

		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			$this->DB_Settings_Syncing->expire_sync();
		}

		parent::complete();

	}


}
