<?php

namespace WPS\Processing;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;
use WPS\Utils\Posts as Utils_Posts;
use WPS\Utils\Data as Utils_Data;
use WPS\Utils\Server;


class Posts_Relationships extends \WPS\Processing\Vendor_Background_Process {

	protected $action = 'wps_background_processing_posts_r';

	protected $DB_Settings_Syncing;
	protected $CPT_Meta;
	protected $CPT_Query;
	protected $DB_Collections;

	public $meta;


	public function __construct($DB_Settings_Syncing, $CPT_Meta, $CPT_Query, $DB_Collections) {

		$this->DB_Settings_Syncing 		= $DB_Settings_Syncing;
		$this->CPT_Meta 							= $CPT_Meta;
		$this->CPT_Query 							= $CPT_Query;
		$this->DB_Collections 				= $DB_Collections;

		// $this->identifier = 'test';

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

		$this->dispatch_items($items);

	}


	/*

	Saves a post warning

	*/
	public function save_post_warning($result) {

		if ( Utils_Posts::is_modify_posts_error($result) ) {
			return $this->DB_Settings_Syncing->save_notice($result, 'warning');
		}

	}


	/*

	Maybe saves a post warning

	*/
	public function maybe_save_post_warnings($post_results) {

		if ( empty($post_results) ) {
			return;
		}

		return array_map([$this, 'save_post_warning'], Utils_Data::return_only_wp_errors($post_results));

	}


	public function post_type_switched($post) {

		if ($this->meta['post_type'] !== $post->post_type) {
			return true;
		}

		return false;

	}


	/*

	Override this method to perform any actions required during the async request.

	*/
	protected function task($post) {

		// Assigns the meta property if either the first task or if a new task type
		if ( empty($this->meta) ) {
			$this->meta = get_option( $this->meta_identifier($post) );
		}


		if ( $this->post_type_switched($post) ) {

			$this->DB_Settings_Syncing->set_finished_relationship($this->meta['post_type']);

			$this->meta = get_option( $this->meta_identifier($post) );

		}


		/*

		Collections is the only thing hardcoded at the moment.

		Need this due to the smart_collections / custom_collections seperation

		*/
		if ($this->meta['lookup_key'] === WPS_COLLECTIONS_LOOKUP_KEY) {



			$post = $this->DB_Collections->get_collections_from_posts($post);

		} else {

			// Responsible for calling $wpdb->get_row potentially thousands of times
			$post = $this->CPT_Meta->add_lookup_key_to_posts([
				'posts' 			=> $post,
				'lookup_key' 	=> $this->meta['lookup_key'],
				'table_name' 	=> $this->meta['table_name'],
			]);

		}



		// Responsible for calling update_column_single and update_post_meta potentially thousands of times
		$results = $this->CPT_Query->modify_posts_relationships([
			'posts'					=> $post,
			'lookup_key'		=> $this->meta['lookup_key'],
			'relationships'	=> $this->meta['relationships']
		]);

		$this->maybe_save_post_warnings($results);

		return false;

	}


	/*

	Complete can be called via both error and success. Therefore, we need
	to check is_syncing() to ensure predictable expiration.

	*/
	protected function complete() {

		$this->DB_Settings_Syncing->set_finished_relationship($this->meta['post_type']);

		parent::complete();

	}

}
