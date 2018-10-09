<?php

namespace WPS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class Async_Processing_Variants extends Vendor_Background_Process {

	protected $action = 'wps_background_processing_variants';

	protected $DB_Settings_Syncing;
	protected $DB_Variants;


	public function __construct($DB_Settings_Syncing, $DB_Variants) {

		$this->DB 												= $DB_Settings_Syncing; // used only for readability
		$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;
		$this->DB_Variants 								= $DB_Variants;
		$this->compatible_charsets				= true;

		parent::__construct();

	}


	/*

	$product represents an array of "products" with only one property -- "variants"

	*/
	protected function task($product) {

		// Stops background process if syncing stops
		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			$this->complete();
			return false;
		}

		// Actual work
		$result = $this->DB_Variants->insert_items_of_type($product->variants);

		if (is_wp_error($result)) {
			$this->DB_Settings_Syncing->save_notice_and_stop_sync($result);
			$this->complete();
			return false;
		}

		return false;

	}


	/*

	Used to increment the syncing current amounts

	*/
	protected function after_queue_item_removal($product) {
		$this->DB_Settings_Syncing->increment_current_amount('products');
	}


	public function before_queue_item_save($items) {

		global $wpdb;

		if ($this->DB->has_compatible_charsets( [$wpdb->prefix . WPS_TABLE_NAME_WP_OPTIONS, $wpdb->prefix . WPS_TABLE_NAME_VARIANTS]) ) {
			return $items;
		}

		$items_serialized_encoded = $this->DB->encode_data( json_encode($items) );

		return json_decode($items_serialized_encoded);

	}


	/*

	Called from the frontend to begin the syncing batch insert

	*/
	public function insert_variants_batch($products) {

		if ( $this->DB->max_packet_size_reached($products) ) {

			$this->DB_Settings_Syncing->save_notice_and_stop_sync( $this->DB_Settings_Syncing->throw_max_allowed_packet() );
			$this->DB_Settings_Syncing->expire_sync();
			$this->complete();

		}

		// Need to copy so as to not change the data undernearth other processes
		$products_copy = $this->DB_Variants->copy($products);

		$products_filtered = Utils::filter_data_except($products_copy, 'variants');


		foreach ($products_filtered as $product) {
			$this->push_to_queue($product);
		}

		$this->save()->dispatch();

	}


	/*

	Called when the syncing completes

	*/
	protected function complete() {

		if (!$this->DB_Settings_Syncing->is_syncing() || $this->DB_Settings_Syncing->all_syncing_complete()) {
			$this->DB_Settings_Syncing->expire_sync();
		}

		parent::complete();

	}

}
