<?php

namespace WPS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Async_Processing_Posts_Collections_Relationships') ) {

  class Async_Processing_Posts_Collections_Relationships extends Vendor_Background_Process {

		protected $action = 'wps_background_processing_collections_r';

		protected $DB_Collections;
		protected $DB_Settings_Connection;
		protected $DB_Settings_Syncing;

		public function __construct($DB_Collections, $DB_Settings_Connection, $DB_Settings_Syncing) {

			$this->DB_Collections 						= $DB_Collections;
			$this->DB_Settings_Connection 		= $DB_Settings_Connection;
			$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;

			parent::__construct();

		}


		/*

		Override this method to perform any actions required during the async request.

		*/
		protected function task($posts) {

			$insertion_results = [];

			$collections = $this->DB_Collections->get_collections_from_posts($posts);

			foreach ($collections as $collection) {

				/*

				Update post meta

				*/
				$update_post_meta_result = $this->DB_Collections->update_post_meta_helper($collection['post_id'], WPS_COLLECTIONS_LOOKUP_KEY, $collection[WPS_COLLECTIONS_LOOKUP_KEY]);

				if (is_wp_error($update_post_meta_result)) {

					$existing_value = get_post_meta($collection['post_id'], WPS_COLLECTIONS_LOOKUP_KEY, true);

					if ($existing_value !== $collection[WPS_COLLECTIONS_LOOKUP_KEY]) {
						$this->DB_Settings_Syncing->save_notice($update_post_meta_result);
						$this->complete();
						return false;
					}

				}


				/*

				Updates the post_id column of the custom collections table

				*/
				$post_id_collection = $this->DB_Collections->set_post_id_to_collection($collection['post_id'], $collection);

				if (is_wp_error($post_id_collection)) {
					$this->DB_Settings_Syncing->save_notice($post_id_collection);
					$this->complete();
					return false;
				}


				$insertion_results[$collection['post_id']]['post_meta'] = $update_post_meta_result;
				$insertion_results[$collection['post_id']]['collection_post_id'] = $post_id_collection;

			}

			return false;

		}


		/*

		Responsible for kicking off the batch

		*/
		public function insert_posts_collections_relationships($posts) {

			if ( $this->DB_Settings_Connection->max_packet_size_reached($posts) ) {

				$this->DB_Settings_Syncing->save_notice_and_stop_sync( $this->DB_Settings_Syncing->throw_max_allowed_packet() );
				$this->DB_Settings_Syncing->expire_sync();
				$this->complete();

			}

			$this->push_to_queue($posts);
			$this->save()->dispatch();

		}


		/*

		When the background process completes ...

		*/
		protected function complete() {

			$this->DB_Settings_Syncing->set_finished_collection_posts_relationships(1);
			parent::complete();

		}

  }

}
