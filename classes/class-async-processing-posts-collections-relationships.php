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
		protected $DB_Collections_Custom;
		protected $DB_Collections_Smart;
		protected $DB_Settings_Connection;
		protected $DB_Settings_Syncing;
		protected $WS_Syncing;


		public function __construct($DB_Collections, $DB_Collections_Custom, $DB_Collections_Smart, $DB_Settings_Connection, $DB_Settings_Syncing, $WS_Syncing) {

			$this->DB_Collections 						= $DB_Collections;
			$this->DB_Collections_Custom 			= $DB_Collections_Custom;
			$this->DB_Collections_Smart 			= $DB_Collections_Smart;
			$this->DB_Settings_Connection 		= $DB_Settings_Connection;
			$this->DB_Settings_Syncing 				= $DB_Settings_Syncing;
			$this->WS_Syncing 								= $WS_Syncing;

			parent::__construct();

		}


		/*

		Override this method to perform any actions required during the async request.

		*/
		protected function task($posts) {

			$collections = [];
			$insertion_results = [];

			foreach ($posts as $post) {

				$collection = $this->DB_Collections->get_collection_from_post_name($post->post_name);

				if (!empty($collection)) {
					$collections[$post->ID]['collection_id'] = $collection->collection_id;

				} else {
					$collections[$post->ID]['collection_id'] = 0;
				}

				$collections[$post->ID]['post_id'] = $post->ID;

				if (!empty($collection->rules)) {
					$collections[$post->ID]['rules'] = $collection->rules;
				}

			}



			foreach ($collections as $collection) {

				/*

				Update post meta

				*/
				$update_post_meta_result = $this->DB_Collections->update_post_meta($collection['post_id'], 'collection_id', $collection['collection_id']);

				if (is_wp_error($update_post_meta_result)) {

					$existing_value = get_post_meta($collection['post_id'], 'collection_id', true);

					if ($existing_value !== $collection['collection_id']) {
						$this->WS_Syncing->save_notice($update_post_meta_result);
						$this->complete();
						return false;
					}

				}


				/*

				Updates the post_id column of the custom collections table

				*/
				$post_id_collection = $this->DB_Collections->set_post_id_to_collection($collection['post_id'], $collection);

				if (is_wp_error($post_id_collection)) {
					$this->WS_Syncing->save_notice($post_id_collection);
					$this->complete();
					return false;
				}


				$insertion_results[$collection['post_id']]['post_meta'] = $update_post_meta_result;
				$insertion_results[$collection['post_id']]['collection_post_id'] = $post_id_collection;

			}

			return false;

		}


		public function insert_posts_collections_relationships($posts) {

			// First check if the wholte data object exceeds packet size ...
			if ($this->DB_Settings_Connection->max_packet_size_reached($posts)) {

				// $items_per_chunk = $this->WS_Syncing->find_amount_to_chunk($posts);
				$posts_chunked = array_chunk($posts, 50);

				foreach ($posts_chunked as $chunk) {
					$this->push_to_queue($chunk);
					$this->save();

				}

				$this->dispatch();

			} else {
				$this->push_to_queue($posts);
				$this->save()->dispatch();
			}

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
