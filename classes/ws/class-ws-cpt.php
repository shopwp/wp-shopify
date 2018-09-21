<?php

namespace WPS\WS;

use WPS\CPT as CPT_Main;
use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('CPT')) {

  class CPT extends \WPS\WS {

		protected $DB;
		protected $Async_Processing_Posts_Products_Relationships;
		protected $Async_Processing_Posts_Collections_Relationships;

		public function __construct($DB, $Async_Processing_Posts_Products_Relationships, $Async_Processing_Posts_Collections_Relationships) {

			$this->DB																									= $DB;
			$this->Async_Processing_Posts_Products_Relationships			= $Async_Processing_Posts_Products_Relationships;
			$this->Async_Processing_Posts_Collections_Relationships		=	$Async_Processing_Posts_Collections_Relationships;

		}


		/*

		Delete Synced Posts
		- Predicate Function (returns boolean)

		*/
		public function delete_posts_by_type($post_type) {

			global $wpdb;

			$query = "DELETE posts, pt, pm FROM " . WPS_TABLE_NAME_WP_POSTS . " posts LEFT JOIN " . WPS_TABLE_NAME_WP_TERM_RELATIONSHIPS . " pt ON pt.object_id = posts.ID LEFT JOIN " . WPS_TABLE_NAME_WP_POSTMETA . " pm ON pm.post_id = posts.ID WHERE posts.post_type = %s";

			$query_prepared = $wpdb->prepare($query, $post_type);

			return $this->DB->query( $query_prepared );

		}


		/*

		Delete posts by ids

		*/
		public function delete_posts_by_ids($ids) {

			global $wpdb;

			if ( !is_array($ids) ) {
				$ids = Utils::wrap_in_array($ids);
			}

			if ( empty($ids) ) {
				return false;
			}

			// how many entries will we select?
			$how_many = count($ids);

			// prepare the right amount of placeholders
			$placeholders = array_fill(0, $how_many, '%d');

			// $format = '%d, %d, %d, %d, %d, [...]'
			$format = Utils::convert_to_comma_string($placeholders);

			$query = "DELETE posts, pt, pm FROM " . WPS_TABLE_NAME_WP_POSTS . " posts LEFT JOIN " . WPS_TABLE_NAME_WP_TERM_RELATIONSHIPS . " pt ON pt.object_id = posts.ID LEFT JOIN " . WPS_TABLE_NAME_WP_POSTMETA . " pm ON pm.post_id = posts.ID WHERE posts.ID IN($format)";

			$query_prepared = $wpdb->prepare($query, $ids);

			return $wpdb->query($query_prepared);

		}


		/*

		Delete taxonomies

		*/
		public function delete_taxonomies($type) {

			if (!taxonomy_exists($type)) {
				return;
			}

			$deletions = [];

			$terms = get_terms([
				'taxonomy' => $type,
				'hide_empty' => false,
			]);

			foreach ($terms as $term) {
				$deletions[] = wp_delete_term( $term->term_id, $type);
			}

			return $deletions;

		}


		/*

		Delete the synced Shopify data

		*/
		public function delete_posts() {

			$results = [];
			// $this->delete_taxonomies('wps_tags'); Not currently used
			$results[WPS_PRODUCTS_POST_TYPE_SLUG] 			= $this->delete_posts_by_type(WPS_PRODUCTS_POST_TYPE_SLUG);
			$results[WPS_COLLECTIONS_POST_TYPE_SLUG] 		= $this->delete_posts_by_type(WPS_COLLECTIONS_POST_TYPE_SLUG);

			return $results;

		}


		/*

		Sets the products posts relationships

		*/
		public function set_product_posts_relationships() {

			$posts_products = Utils::lessen_array_by( CPT_Main::get_all_posts('wps_products'), ['ID', 'post_name']);

			$this->Async_Processing_Posts_Products_Relationships->insert_posts_products_relationships($posts_products);

			$this->send_success();

		}


		/*

		Sets the collection posts relationships

		*/
		public function set_collection_posts_relationships() {

			$posts_collections = Utils::lessen_array_by( CPT_Main::get_all_posts('wps_collections'), ['ID', 'post_name']);

			$this->Async_Processing_Posts_Collections_Relationships->insert_posts_collections_relationships($posts_collections);

			$this->send_success();

		}


		/*

		Hooks

		*/
		public function hooks() {

			add_action('wp_ajax_set_product_posts_relationships', [$this, 'set_product_posts_relationships']);
			add_action('wp_ajax_nopriv_set_product_posts_relationships', [$this, 'set_product_posts_relationships']);

			add_action('wp_ajax_set_collection_posts_relationships', [$this, 'set_collection_posts_relationships']);
			add_action('wp_ajax_nopriv_set_collection_posts_relationships', [$this, 'set_collection_posts_relationships']);

		}


	  /*

	  Register

	  */
	  public function init() {
			$this->hooks();
	  }

  }


}
