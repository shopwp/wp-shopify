<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}


class Async_Processing_Collections_Smart extends Vendor_Background_Process {

	protected $action = 'wps_background_processing_collections_smart';

	protected $DB_Settings_Syncing;
	protected $DB_Collections;


	public function __construct($DB_Settings_Syncing, $DB_Collections) {

		$this->DB_Settings_Syncing				=	$DB_Settings_Syncing;
		$this->DB_Collections 						= $DB_Collections;

		parent::__construct();

	}


	/*

	Override this method to perform any actions required during the async request.

	*/
	protected function task($smart_collection) {

		// Stops background process if syncing stops
		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			$this->complete();
			return false;
		}

		// Actual work
		$result = $this->DB_Collections->insert_items_of_type( $this->DB_Collections->mod_before_change($smart_collection) );

		// Save warnings if exist ...
		$this->DB_Settings_Syncing->maybe_save_warning_from_insert($result, 'Collection', $smart_collection->id);


		if (is_wp_error($result)) {
			$this->DB_Settings_Syncing->save_notice_and_stop_sync($result);
			$this->complete();
			return false;
		}

		return false;

	}


	protected function after_queue_item_removal($smart_collection) {
		$this->DB_Settings_Syncing->increment_current_amount('smart_collections');
	}


	public function insert_smart_collections_batch($smart_collections) {

		if ( $this->DB_Settings_Syncing->max_packet_size_reached($smart_collections) ) {

			$this->DB_Settings_Syncing->save_notice_and_stop_sync( $this->DB_Settings_Syncing->throw_max_allowed_packet() );

			$this->DB_Settings_Syncing->expire_sync();
			$this->complete();

		}

		foreach ($smart_collections as $key => $smart_collection) {
			$this->push_to_queue($smart_collection);
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
