<?php

namespace WPS\Processing;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;
use WPS\Utils\Server;


class Collections_Smart extends \WPS\Processing\Vendor_Background_Process {

	protected $action = 'wps_background_processing_collections_smart';

	public $DB_Settings_Syncing;
	public $DB_Collections;


	public function __construct($DB_Settings_Syncing, $DB_Collections) {

		$this->DB_Settings_Syncing				=	$DB_Settings_Syncing;
		$this->DB_Collections 						= $DB_Collections;

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
	protected function task($item) {

		// Stops background process if syncing stops
		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			$this->complete();
			return false;
		}

		// Actual work
		$result = $this->DB_Collections->insert_items_of_type( $this->DB_Collections->mod_before_change($item) );

		// Save warnings if exist ...
		$this->DB_Settings_Syncing->maybe_save_warning_from_insert($result, 'Collection', $item->id);


		if (is_wp_error($result)) {
			$this->DB_Settings_Syncing->save_notice_and_expire_sync($result);
			$this->complete();
			return false;
		}

		return false;

	}


	/*

	After an individual task item is removed from the queue

	*/
	protected function after_queue_item_removal($item) {
		$this->DB_Settings_Syncing->increment_current_amount('smart_collections');
	}


}
