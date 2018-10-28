<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}


class Async_Processing_Images extends Vendor_Background_Process {

	protected $action = 'wps_background_processing_images';

	protected $DB_Settings_Syncing;
	protected $DB_Images;


	public function __construct($DB_Settings_Syncing, $DB_Images) {

		$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;
		$this->DB_Images 									= $DB_Images;

		parent::__construct();

	}


	/*

	Runs for each product

	*/
	protected function task($product) {

		// Stops background process if syncing stops
		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			$this->complete();
			return false;
		}

		// Actual work
		$result = $this->DB_Images->insert_items_of_type($product->images);

		if (is_wp_error($result)) {
			$this->DB_Settings_Syncing->save_notice_and_stop_sync($result);
			$this->complete();
			return false;
		}

		return false;

	}


	protected function after_queue_item_removal($product) {
		$this->DB_Settings_Syncing->increment_current_amount('products');
	}


	/*

	Inserts all products -- runs a background process to prevent timeouts

	*/
	public function insert_images_batch($products) {

		if ( $this->DB_Settings_Syncing->max_packet_size_reached($products) ) {

			$this->DB_Settings_Syncing->save_notice_and_stop_sync( $this->DB_Settings_Syncing->throw_max_allowed_packet() );
			$this->DB_Settings_Syncing->expire_sync();
			$this->complete();

		}

		foreach ($products as $product) {
			$this->push_to_queue($product);
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
