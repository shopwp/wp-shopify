<?php

namespace WPS\Processing;

if (!defined('ABSPATH')) {
	exit;
}


use WPS\Utils;
use WPS\Utils\Server;


class Collects extends \WPS\Processing\Vendor_Background_Process {

	protected $action = 'wps_background_processing_collects';

	protected $DB_Settings_Syncing;
	protected $DB_Collects;

	public function __construct($DB_Settings_Syncing, $DB_Collects) {

		$this->DB_Settings_Syncing				=	$DB_Settings_Syncing;
		$this->DB_Collects 								= $DB_Collects;

		parent::__construct($DB_Settings_Syncing);

	}


	public function add_difference_if_exists($items, $only_published_collects) {

		$difference_amount = $this->DB_Collects->find_published_difference_to_add( $items, $only_published_collects );

		if ( $difference_amount === 0 ) {
			return;
		}

		$this->DB_Settings_Syncing->increment_current_amount('collects', $difference_amount);

	}


	/*

	Entry point. Initial call before processing starts.

	*/
	public function process($items, $params = false) {

		if ( $this->expired_from_server_issues($items, __METHOD__, __LINE__) ) {
			return;
		}

		$only_published_collects = $this->DB_Collects->get_published_collects( $items, $this->DB_Settings_Syncing->get_published_product_ids() );

		// Only empty if all 250 collects are not published
		if ( empty($only_published_collects) ) {
			return $this->DB_Settings_Syncing->increment_current_amount( 'collects', count($items) );
		}

		$this->add_difference_if_exists($items, $only_published_collects);

		$this->dispatch_items($only_published_collects);

	}


	/*

	Performs actions required for each item in the queue

	*/
	protected function task($collect) {

		// Stops background process if syncing stops
		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			$this->complete();
			return false;
		}

		// Actual work
		$result = $this->DB_Collects->insert_items_of_type( $this->DB_Collects->mod_before_change($collect) );

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
	protected function after_queue_item_removal($collect) {
		$this->DB_Settings_Syncing->increment_current_amount('collects');
	}


}
