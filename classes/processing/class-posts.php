<?php

namespace WPS\Processing;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\CPT;
use WPS\Utils;
use WPS\Utils\Server;


class Posts extends \WPS\Processing\Vendor_Background_Process {

	protected $action = 'wps_background_processing_posts';
	protected $DB_Settings_Syncing;
	protected $CPT_Query;
	public $meta;

	public function __construct($DB_Settings_Syncing, $CPT_Query) {

		$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;
		$this->CPT_Query 									= $CPT_Query;

		parent::__construct($DB_Settings_Syncing);

	}


	/*

	Entry point. Initial call before processing starts.

	*/
	public function process($items, $params = false) {

		if ($params) {
			$this->add_meta_to_batch($params);
		}

		if ( $this->expired_from_server_issues($items, __METHOD__, __LINE__) ) {
			return;
		}

		$this->dispatch_items($items, true);

	}


	/*

	Post type switched

	*/
	public function post_type_switched($item) {

		if ( is_array($item) ) {
			$item = $item[0];
		}

		if ($this->meta['increment_name'] !== $item->increment_name) {
			return true;
		}

		return false;

	}


	/*

	Three scenarios could exists:
		a. Zero posts exist 															-- INSERT only
		b. less posts than data exist (new products)			-- Both UPDATE and INSERT only
		c. the same amount of posts and data exists				-- UPDATE only

	*/
	protected function task($items) {

		// Stops background process if syncing stops
		if ( !$this->DB_Settings_Syncing->is_syncing() ) {
			$this->complete();
			return false;
		}

		// Assigns the meta property if either the first task or if a new task type
		if ( empty($this->meta) ) {
			$this->meta = get_option( $this->meta_identifier($items) );
		}

		if ( $this->post_type_switched($items) ) {
			$this->meta = get_option( $this->meta_identifier($items) );
		}

		$result = $this->CPT_Query->modify_posts([
			'items'				=> $items,
			'post_type'		=> $this->meta['post_type'],
			'totals'			=> $this->DB_Settings_Syncing->syncing_totals_by_type($this->meta['post_type'])
		]);


		if ( is_wp_error($result) ) {

			$this->DB_Settings_Syncing->save_notice_and_expire_sync($result);
			$this->complete();

		}

		return false;

	}


	/*

	After an individual task item is removed from the queue

	*/
	protected function after_queue_item_removal($items) {
		$this->DB_Settings_Syncing->increment_current_amount($this->meta['increment_name'], count( Utils::maybe_wrap_in_array($items) ) );
	}


}
