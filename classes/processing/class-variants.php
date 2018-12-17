<?php

namespace WPS\Processing;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;
use WPS\Utils\Server;


class Variants extends \WPS\Processing\Vendor_Background_Process {

	protected $action = 'wps_background_processing_variants';

	protected $DB_Settings_Syncing;
	protected $DB_Variants;


	public function __construct($DB_Settings_Syncing, $DB_Variants) {

		$this->DB 												= $DB_Settings_Syncing; // used only for readability
		$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;
		$this->DB_Variants 								= $DB_Variants;
		$this->compatible_charsets				= true;

		parent::__construct($DB_Settings_Syncing);

	}


	/*

	Entry point. Initial call before processing starts.

	*/
	public function process($items, $params = false) {

		if ( $this->expired_from_server_issues($items, __METHOD__, __LINE__) ) {
			return;
		}

		// Need to copy so as to not change the data undernearth other processes
		$items_filtered = Utils::filter_data_except($this->DB_Variants->copy($items), 'variants');


		$this->dispatch_items($items_filtered);

	}


	/*

	Performs actions required for each item in the queue

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
			$this->DB_Settings_Syncing->save_notice_and_expire_sync($result);
			$this->complete();
			return false;
		}

		return false;

	}


	/*

	Before an individual task item is added to the queue

	*/
	public function before_queue_item_save($items) {

		global $wpdb;

		if ($this->DB->has_compatible_charsets( [$wpdb->prefix . WPS_TABLE_NAME_WP_OPTIONS, $wpdb->prefix . WPS_TABLE_NAME_VARIANTS]) ) {
			return $items;
		}

		$items_serialized_encoded = $this->DB->encode_data( json_encode($items) );

		return json_decode($items_serialized_encoded);

	}


	/*

	After an individual task item is removed from the queue

	*/
	protected function after_queue_item_removal($product) {
		$this->DB_Settings_Syncing->increment_current_amount('products');
	}
	

}
