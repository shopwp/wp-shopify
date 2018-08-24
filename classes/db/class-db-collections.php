<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\CPT;
use WPS\Transients;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Collections')) {

  class Collections extends \WPS\DB {

		private $DB_Collects;
		private $WS_Collects;
		private $CPT_Model;

		private $Collections_Smart;
		private $Collections_Custom;


  	public function __construct($DB_Collects, $WS_Collects, $CPT_Model, $Collections_Smart, $Collections_Custom) {

			$this->DB_Collects 								= $DB_Collects;
			$this->WS_Collects 								= $WS_Collects;
			$this->CPT_Model 									= $CPT_Model;

			$this->Collections_Smart 					= $Collections_Smart;
			$this->Collections_Custom 				= $Collections_Custom;

    }


		public function get_all_collections_query() {

			return "SELECT
			smart.collection_id,
			smart.post_id,
			smart.title,
			smart.handle,
			smart.body_html,
			smart.image,
			smart.sort_order,
			smart.published_at,
			smart.updated_at,
			smart.rules
			FROM " . WPS_TABLE_NAME_COLLECTIONS_SMART . " smart

			UNION

			SELECT
			custom.collection_id,
			custom.post_id,
			custom.title,
			custom.handle,
			custom.body_html,
			custom.image,
			custom.sort_order,
			custom.published_at,
			custom.updated_at,
			NULL as rules
			FROM " . WPS_TABLE_NAME_COLLECTIONS_CUSTOM . " custom";

		}


		/*

		Gets all collections

		*/
		public function get_collections() {

			$collections_cached = Transients::get('wps_all_collections');

			if ( !empty($collections_cached) ) {
				return $collections_cached;
			}

			global $wpdb;

			$results = $wpdb->get_results( $this->get_all_collections_query() );

			Transients::set('wps_all_collections', $results);

			return $results;

		}


		/*

	  Get Collection

	  */
		public function get_collection($postID = null) {

	    global $wpdb;
			global $post;

	    if ($postID === null && is_object($post)) {
	      $postID = $post->ID;
	    }

	    $query = "SELECT
			smart.collection_id,
			smart.post_id,
			smart.title,
			smart.handle,
			smart.body_html,
			smart.image,
			smart.sort_order,
			smart.published_at,
			smart.updated_at,
			smart.rules
			FROM " . WPS_TABLE_NAME_COLLECTIONS_SMART . " smart WHERE smart.post_id = $postID

			UNION

			SELECT
			custom.collection_id,
			custom.post_id,
			custom.title,
			custom.handle,
			custom.body_html,
			custom.image,
			custom.sort_order,
			custom.published_at,
			custom.updated_at,
			NULL as rules
			FROM " . WPS_TABLE_NAME_COLLECTIONS_CUSTOM . " custom WHERE custom.post_id = $postID;";


			/*

			Caching mecahnism for collections. Used also by products

			*/
			if (get_transient('wps_collection_single_' . $postID)) {
				$results = get_transient('wps_collection_single_' . $postID);

			} else {
				$results = $wpdb->get_results($query);
				set_transient('wps_collection_single_' . $postID, $results);

			}

			return $results;

	  }


		/*

		Get collections by product ID

		*/
		public function get_collections_by_product_id($product_id) {

			$results_cached = Transients::get('wps_collections_by_product_id_' . $product_id);

			if ( !empty($results_cached) ) {
				return $results_cached;
			}

			$collects = $this->DB_Collects->get_collects_by_product_id($product_id);
			$allCollections = $this->get_collections();
			$results = [];

			foreach ( $allCollections as $collection ) {

				if (in_array($collection->collection_id, array_column($collects, 'collection_id'))) {
					$results[] = $collection;
				}

			}

			Transients::set('wps_collections_by_product_id_' . $product_id, $results);

			return $results;

		}





		/*

		Default Collections Query

		*/
		public function get_default_collections_query($clauses = '') {

			global $wpdb;

			return [
				'where' => '',
				'groupby' => '',
				'join' => ' INNER JOIN (

				SELECT
				smart.collection_id,
				smart.post_id,
				smart.title,
				smart.handle,
				smart.body_html,
				smart.image,
				smart.sort_order,
				smart.published_at,
				smart.updated_at
				FROM ' . WPS_TABLE_NAME_COLLECTIONS_SMART . ' smart

				UNION ALL

				SELECT
				custom.collection_id,
				custom.post_id,
				custom.title,
				custom.handle,
				custom.body_html,
				custom.image,
				custom.sort_order,
				custom.published_at,
				custom.updated_at
				FROM ' . WPS_TABLE_NAME_COLLECTIONS_CUSTOM .' custom

			) as collections ON ' . WPS_TABLE_NAME_WP_POSTS . '.ID = collections.post_id',
				'orderby' => $wpdb->posts . '.menu_order',
				'distinct' => '',
				'fields' => 'collections.*',
				'limits' => ''
			];

		}





		public function collection_was_deleted($collection) {

			if (Utils::has($collection, 'published_at') && $collection->published_at !== null) {
				return false;

			} else {
				return true;
			}

		}



		/*

	  Used to check the type of collection
	  - Predicate Function (returns boolean)

	  */
	  public function is_smart_collection($collection) {
			return Utils::has($collection, 'rules') ? true : false;
	  }



		public function standardize_id($collection) {

			if (Utils::has($collection, 'id') && !Utils::has($collection, 'collection_id')) {
				$collection->collection_id = $collection->id;
				return $collection;
			}

			if (Utils::has($collection, 'collection_id') && !Utils::has($collection, 'id')) {
				$collection->id = $collection->collection_id;
				return $collection;
			}

		}




		public function set_default_collection_image($collection) {

			$collection = Utils::flatten_collections_image_prop($collection);

			if (!isset($collection->image)) {

				if ($this->is_smart_collection($collection)) {
					return $this->Collections_Smart->update_column_single( ['image' => null], ['collection_id' => $collection->collection_id] );

				} else {
					return $this->Collections_Custom->update_column_single( ['image' => null], ['collection_id' => $collection->collection_id] );

				}

			}


		}


		public function find_post_id_from_collection_id($collection) {

			if ($this->is_smart_collection($collection)) {
				$collection_found = $this->Collections_Smart->get($collection->id);

			} else {
				$collection_found = $this->Collections_Custom->get($collection->id);
			}

			if (empty($collection_found)) {
				return [];
			}

			return [$collection_found->post_id];

		}


		/*

		Responsible only for determining which type of collection to insert.

		*/
		public function create_collection($collection) {

			if ($this->collection_exists_by_id($collection->collection_id)) {
				return [];
			}

			$results = [];
			$all_collections = CPT::get_all_posts_by_type(WPS_COLLECTIONS_POST_TYPE_SLUG);

			if ($this->is_smart_collection($collection)) {
				$results['create_smart_table'] = $this->Collections_Smart->insert_smart_collection($collection);

			} else {
				$results['create_custom_table'] = $this->Collections_Custom->insert_custom_collection($collection);
			}

			$results['create_post'] = $this->CPT_Model->insert_or_update_collection($all_collections, $collection);
			$results['create_post_meta'] = $this->update_post_meta($results['create_post'], 'collection_id', $collection->collection_id);

			$results['create_collects'] = $this->WS_Collects->update_collects_from_collection_id($collection->collection_id);
			$results['create_set_default_image'] = $this->set_default_collection_image($collection);

			$results['set_post_id_custom_table'] = $this->set_post_id_to_collection($results['create_post'], $collection);

			return $results;

		}


		/*

	  Fired when product is deleted at Shopify

		Deletes collection from custom table

	  */
	  public function delete_collection($collection) {

			$results = [];

			if ($this->is_smart_collection($collection)) {
				$results['delete_smart_table'] = $this->Collections_Smart->delete($collection->id);

			} else {
				$results['delete_custom_table'] = $this->Collections_Custom->delete($collection->id);
			}

			$results['delete_collects'] = $this->DB_Collects->delete_collects_from_collection_id($collection->id);

			return $results;

	  }


		/*

		Responsible only for determining which type of collection to insert.

		Need to update:

		1. Custom table
		2. Posts and Post meta
		3. Collects table

		*/
		public function update_collection($collection) {

			$results = [];
			$all_collections = CPT::get_all_posts_by_type(WPS_COLLECTIONS_POST_TYPE_SLUG);
			$collection = Utils::flatten_collections_image_prop($collection);

			if ($this->is_smart_collection($collection)) {
				$results['update_smart_table'] = $this->Collections_Smart->update($collection->id, $collection);

			} else {
				$results['update_custom_table'] = $this->Collections_Custom->update($collection->id, $collection);
			}

			$results['update_post'] = $this->CPT_Model->insert_or_update_collection($all_collections, $collection);
			$results['update_collects'] = $this->WS_Collects->update_collects_from_collection_id($collection->id);

			return $results;

		}



		public function collection_exists_by_id($collection_id) {

			if (empty($collection_id)) {
				return false;
			}

			$smart_collection_found = $this->Collections_Smart->get($collection_id);
			$custom_collection_found = $this->Collections_Custom->get($collection_id);

			if ( empty($smart_collection_found) && empty($custom_collection_found)) {
		    return false;

		  } else {
		    return true;
		  }

		}


    /*

  	Responsible for assigning a post_id to collection_id

  	*/
		public function set_post_id_to_collection($post_id, $collection) {

			$collection = Utils::convert_array_to_object($collection);

			if ($this->is_smart_collection($collection)) {
				$update_result = $this->Collections_Smart->update_column_single(['post_id' => $post_id], ['collection_id' => $collection->collection_id]);

			} else {
				$update_result = $this->Collections_Custom->update_column_single(['post_id' => $post_id], ['collection_id' => $collection->collection_id]);
			}

			return $this->sanitize_db_response($update_result);

		}


		/*

		Gets collections from post name

		*/
		public function get_collection_from_post_name($post_name = false) {

	    global $wpdb;

	    if ($post_name === false) {
	      return;
	    }

			$query = "SELECT
			smart.collection_id,
			smart.rules
			FROM " . WPS_TABLE_NAME_COLLECTIONS_SMART . " smart WHERE smart.handle = %s

			UNION

			SELECT
			custom.collection_id,
			NULL as rules
			FROM " . WPS_TABLE_NAME_COLLECTIONS_CUSTOM . " custom WHERE custom.handle = %s;";

			$results = $wpdb->get_row( $wpdb->prepare($query, $post_name, $post_name) );

	    return $results;

		}


		/*

		Gets collections from post name

		*/
		public function get_collections_from_ids($collection_ids = []) {

			global $wpdb;

			if (empty($collection_ids)) {
				return $collection_ids;
			}

			$collection_ids = maybe_unserialize($collection_ids);
			$collection_ids = $this->convert_array_to_in_string($collection_ids);

			$query = "SELECT
			smart.collection_id
			FROM " . WPS_TABLE_NAME_COLLECTIONS_SMART . " smart WHERE smart.collection_id IN " . $collection_ids . "

			UNION

			SELECT
			custom.collection_id
			FROM " . WPS_TABLE_NAME_COLLECTIONS_CUSTOM . " custom WHERE custom.collection_id IN " . $collection_ids .  ";";

			return $wpdb->get_results($query, ARRAY_A);

		}


  }

}
