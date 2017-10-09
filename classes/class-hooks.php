<?php

namespace WPS;

use WPS\Utils;
use WPS\DB;
use WPS\DB\Products;
use WPS\DB\Variants;
use WPS\DB\Collections_Smart;
use WPS\DB\Collections_Custom;
use WPS\DB\Settings_General;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}

/*

Public Class

*/

if (!class_exists('Hooks')) {

	class Hooks {


		protected static $instantiated = null;
		private $Config;

    /*

    Initialize the class and set its properties.

    */
    public function __construct($Config) {
      $this->config = $Config;
    }


		/*

		Creates a new class if one hasn't already been created.
		Ensures only one instance is used.

		*/
		public static function instance($Config) {

			if (is_null(self::$instantiated)) {
				self::$instantiated = new self($Config);
			}

			return self::$instantiated;

		}


    /*

    Products Loop - Before Products Loop

    */
		public function wps_products_loop_start($query) {
			return include($this->config->plugin_path . 'public/partials/products/loop/loop-start.php');
		}


    /*

    Products Loop - After Products Loop

    */
		public function wps_products_loop_end($products) {
			return include($this->config->plugin_path . 'public/partials/products/loop/loop-end.php');
		}


    /*

    Products Loop - Products List Opening Tag

    */
		public function wps_products_item_start($product, $args, $customArgs) {
			return include($this->config->plugin_path . 'public/partials/products/loop/item-start.php');
		}


    /*

    Products Loop - Products List Closing Tag

    */
		public function wps_products_item_end($product) {
			return include($this->config->plugin_path . 'public/partials/products/loop/item-end.php');
		}


    /*

    Products Loop - Product

    */
		public function wps_products_item($product, $args, $settings) {
			return include($this->config->plugin_path . 'public/partials/products/loop/item.php');
		}


    /*

    Products Loop - Before Product

    */
		public function wps_products_item_before($product) {
			// return include($this->config->plugin_path . 'public/partials/products/loop/item-link-start.php');
		}


		/*

    Products Loop - After Product

    */
		public function wps_products_item_after($product) {
			// return include($this->config->plugin_path . 'public/partials/products/loop/item-link-end.php');
		}


		/*

    Products Loop - Link Start

    */
		public function wps_products_item_link_start($product, $settings) {
			return include($this->config->plugin_path . 'public/partials/products/loop/item-link-start.php');
		}


		/*

		Products Loop - Link End

		*/
		public function wps_products_item_link_end($product) {
			return include($this->config->plugin_path . 'public/partials/products/loop/item-link-end.php');
		}


    /*

    Products Loop - Image

    */
		public function wps_products_img($product) {
			return include($this->config->plugin_path . 'public/partials/products/loop/item-img.php');
		}


    /*

    Products Loop - Title

    */
		public function wps_products_title($product) {
			return include($this->config->plugin_path . 'public/partials/products/loop/item-title.php');
		}


		/*

		Products Loop - Add to cart

		*/
		public function wps_products_add_to_cart($productWithVariants) {
			return include($this->config->plugin_path . 'public/partials/products/loop/item-add-to-cart.php');
		}


    /*

    Products Loop - Price

    */
		public function wps_products_price($product) {
			return include($this->config->plugin_path . 'public/partials/products/loop/item-price.php');
		}


		/*

		Products Loop - Header

		*/
		public function wps_products_header($query) {
			return include($this->config->plugin_path . 'public/partials/products/loop/header.php');
		}












		/*

		Products Loop - Meta Start

		*/
		public function wps_products_meta_start($product) {
			return include($this->config->plugin_path . 'public/partials/products/add-to-cart/meta-start.php');
		}


		/*

		Products Loop - Quantity

		*/
		public function wps_products_quantity($product) {
			return include($this->config->plugin_path . 'public/partials/products/add-to-cart/quantity.php');
		}


		/*

		Products Loop - Actions Group Start

		*/
		public function wps_products_actions_group_start($product) {
			return include($this->config->plugin_path . 'public/partials/products/add-to-cart/action-groups-start.php');
		}


		/*

		Products Loop - Options

		*/
		public function wps_products_options($product) {
			return include($this->config->plugin_path . 'public/partials/products/add-to-cart/options.php');
		}


		/*

		Products Loop - Button add to cart

		*/
		public function wps_products_button_add_to_cart($product) {
			return include($this->config->plugin_path . 'public/partials/products/add-to-cart/button-add-to-cart.php');
		}


		/*

		Products Loop - Actions Groups End

		*/
		public function wps_products_actions_group_end($product) {
			return include($this->config->plugin_path . 'public/partials/products/add-to-cart/action-groups-end.php');
		}


		/*

		Products Loop - Notice

		*/
		public function wps_products_notice_inline($product) {
			return include($this->config->plugin_path . 'public/partials/products/add-to-cart/notice-inline.php');
		}


		/*

		Products Loop - Meta end

		*/
		public function wps_products_meta_end($product) {
			return include($this->config->plugin_path . 'public/partials/products/add-to-cart/meta-end.php');
		}















		/*

		Products Loop - After

		*/
		public function wps_products_after($products) {

		}


		/*

		Products Loop - No Results

		*/
		public function wps_products_no_results($args) {
			return include($this->config->plugin_path . 'public/partials/products/loop/no-results.php');
		}



    /*

    Products Pagination

    */
		public function wps_products_pagination($productsQuery) {

			$Utils = new Utils();
			$args = array(
				'query' => $productsQuery
			);

			echo $Utils->wps_get_paginated_numbers($args);

		}


		/*

		wps_products_custom_args

		*/
		public function wps_products_custom_args($args) {

			return array(
				'items_per_row' => apply_filters('wps_products_custom_args_items_per_row', 3)
			);

		}


		/*

		wps_collections_custom_args

		*/
		public function wps_collections_custom_args() {

			return array(
				'items_per_row' => apply_filters('wps_collections_custom_args_items_per_row', 3)
			);

		}


		/*

		OWN HOOKS

		*/
		public function wps_products_custom_args_items_per_row($items_per_row) {
			return 3;
		}

		public function wps_collections_custom_args_items_per_row($items_per_row) {
			return 4;
		}


		/*

		Related Products Config

		*/
		public function wps_products_related_custom_args() {

			return array(
				'items_per_row' => apply_filters('wps_products_related_custom_items_per_row', 4)
			);

		}


		/*

		wps_products_related_custom_items_per_row

		*/
		public function wps_products_related_custom_items_per_row($items_per_row) {
			return 4;
		}


		/*

		Before Products Pagination Counter

		*/
		public function wps_products_pagination_start() {
			ob_start();
			include($this->config->plugin_path . 'public/partials/pagination/start.php');
			$output = ob_get_clean();
			return $output;
		}


		/*

		wps_products_related_start

		*/
		public function wps_products_related_start() {
			return include($this->config->plugin_path . 'public/partials/products/related/start.php');
		}


		/*

		wps_products_related_end

		*/
		public function wps_products_related_end() {
			return include($this->config->plugin_path . 'public/partials/products/related/end.php');
		}


		/*

		wps_products_related_heading_start

		*/
		public function wps_products_related_heading_start() {
			return include($this->config->plugin_path . 'public/partials/products/related/heading-start.php');
		}





		/*

		wps_products_related_heading_end

		*/
		public function wps_products_related_heading_end() {
			return include($this->config->plugin_path . 'public/partials/products/related/heading-end.php');
		}


		/*

		After Products Pagination Counter

		*/
		public function wps_products_pagination_end() {
			ob_start();
			include($this->config->plugin_path . 'public/partials/pagination/end.php');
			$output = ob_get_clean();
			return $output;
		}


		/*

		After Products Pagination Counter

		*/
		public function wps_products_pagination_first_page_text() {
			return 'First';
		}


		/*

		wps_products_pagination_next_link_text

		*/
		public function wps_products_pagination_next_link_text() {
			return 'Next YO';
		}


		/*

		wps_products_pagination_prev_link_text

		*/
		public function wps_products_pagination_prev_link_text() {
			return 'Prev YO';
		}


		/*

		wps_products_pagination_prev_page_text

		*/
		public function wps_products_pagination_prev_page_text() {
			return '<<';
		}


		/*

		wps_products_pagination_next_page_text

		*/
		public function wps_products_pagination_next_page_text() {
			return '>>';
		}


		/*

		wps_products_pagination_next_page_text

		*/
		public function wps_products_pagination_show_as_prev_next() {
			return false;
		}


		/*

		wps_products_pagination_next_page_text

		*/
		public function wps_products_pagination_range() {
			return 5;
		}


		/*

		Single Tempt after stuffz

		*/
		public function wps_related_products() {

			if (!is_single()) {
				return;

			} else {
				include($this->config->plugin_path . "public/templates/products-related.php");

			}

		}

		public function wps_products_related_before() {
			echo 'wps_products_related_before';
		}

		public function wps_products_related_after() {
			echo 'wps_products_related_after';
		}

		public function wps_products_related_heading_before() {
			echo 'wps_products_related_heading_before';
		}

		public function wps_products_related_heading() {
			echo 'Related';
		}

		public function wps_products_related_heading_end_after() {
			echo 'wps_products_related_heading_end_after';
		}


		/*

		wps_products_join

		*/
		public function wps_products_join($sql, $query) {

			global $wpdb;

			$DB_Products = new Products();
			$DB_Variants = new Variants();

	    $table_products = $DB_Products->get_table_name();
			$table_variants = $DB_Variants->get_table_name();

			if ($query->get('context') === 'wps_products_query') {

				/*

				User passed in custom shortcode filtering options

				*/
				if ($query->get('custom')) {

					$sql = Utils::construct_join_from_products_shortcode($query->get('custom'));


				} else {

					$sql .= " INNER JOIN $table_products ON " .
						 $wpdb->posts . ".ID = " . $table_products .
						 ".post_id ";

					$sql .= " INNER JOIN " . $table_variants . " ON " .
						 $table_products . ".product_id = " . $table_variants .
						 ".product_id AND " . $table_variants . ".position = 1";

				}

			} else if ($query->get('context') === 'wps_collections_query') {

				$DB_Collections_Custom = new Collections_Custom();
				$table_collections_custom = $DB_Collections_Custom->get_table_name();

				$DB_Collections_Smart = new Collections_Smart();
				$table_collections_smart = $DB_Collections_Smart->get_table_name();

				$sql .= " INNER JOIN " . $table_collections_custom . " ON " .
					 $wpdb->posts . ".ID = " . $table_collections_custom .
					 ".post_id ";

 				$sql .= " INNER JOIN " . $table_collections_smart . " ON " .
 				 $wpdb->posts . ".ID = " . $table_collections_smart .
 				 ".post_id ";

			}

			return $sql;

		}




		/*

		wps_clauses_mod

		*/
		public function wps_clauses_mod($clauses, $query) {

			if (!is_admin()) {

				global $wpdb;
				global $post;

				$DB_Products = new Products();
				$DB_Collections_Smart = new Collections_Smart();
				$DB_Collections_Custom = new Collections_Custom();
				$DB = new DB();

				if ($query->get('context') === 'wps_products_query') {

					// If using Shortcode ...
					if ($query->get('custom')) {

						$clauses = Utils::construct_clauses_from_products_shortcode($query->get('custom'), $query);

					} else {

						$clauses = $DB_Products->get_default_query();

						/*

						TODO: This seems hard to maintain / remember. These three lines force random
						on related products. User might not want random ... need ability to customize.

						*/
						if ($query->get('wps_related_products')) {
							$clauses['orderby'] = 'RAND()';
						}

					}

				} else if ($query->get('context') === 'wps_collections_query') {

					// If Shortcode has attributes passed in ...
					if ($query->get('custom')) {
						$clauses = Utils::construct_clauses_from_collections_shortcode($query->get('custom'), $query);

					} else {

						$clauses = $DB->get_default_collections_query($clauses);

					}

				}


				// TODO: Revisit, make better
				if ($query->get('context') === 'wps_products_query' || $query->get('context') === 'wps_collections_query') {

					if (empty($clauses['limits'])) {

						/*

						This check is needed so as not to override any additional loops on the page.
						TODO: Do research to ensure more additional loops aren't affected

						*/
						if (isset($post->post_content)) {
						  $content = $post->post_content;

						  if( has_shortcode( $content, 'wps_products' ) || has_shortcode( $content, 'wps_collections' ) ) {
								// $clauses['limits'] = Utils::construct_pagination_limits($query);
						  }

						}

						$clauses['limits'] = Utils::construct_pagination_limits($query);

					}

				}

			}

			return $clauses;

		}


		/*

		Products Display Wrapper

		*/
		public function wps_products_display($args, $customArgs) {

			if (!is_admin()) {

				global $wpdb;

				$Utils = new Utils();

				$args['context'] = 'wps_products_query';

				if (is_single()) {
					$args['is_single'] = true;

				} else {
					$args['is_single'] = false;

				}


				$productQueryHash = md5(serialize($args));


				/*

				Here we're caching an entire WP_Query response by hashing the
				argument array. We can safely assume that a given set of args
				will always produce the same list of products assuming the
				product data doesn't change. Therefore it's important that we clear
				this cache whenever a product is updated, created, or deleted.

				*/
		    if (get_transient('wps_products_query_hash_cache_' . $productQueryHash)) {
		      $productsQuery = get_transient('wps_products_query_hash_cache_' . $productQueryHash);

		    } else {

					$productsQuery = new \WP_Query($args);
		      set_transient('wps_products_query_hash_cache_' . $productQueryHash, $productsQuery);

		    }


				if (Utils::wps_is_manually_sorted($args)) {

					$wps_products = Utils::wps_manually_sort_posts_by_title($args['custom']['titles'], $productsQuery->posts);

				} else {

					$wps_products = $productsQuery->posts;

				}


				// Adding feature imaged to object
				foreach ($wps_products as $wps_product) {
		      $wps_product->feat_image = $Utils->get_feat_image_by_id($wps_product->post_id);
		    }


				$amountOfProducts = count($wps_products);

				$settings = $this->config->wps_get_settings_general();

				do_action( 'wps_products_before', $productsQuery );
				do_action( 'wps_products_header', $productsQuery );


				if ($amountOfProducts > 0) {

					do_action( 'wps_products_loop_start', $productsQuery );

					foreach($wps_products as $wps_product) {

						do_action( 'wps_products_item_start', $wps_product, $args, $customArgs );
						do_action( 'wps_products_item', $wps_product, $args, $settings );
						do_action( 'wps_products_item_end', $wps_product );

					}

					wp_reset_postdata();

					do_action( 'wps_products_loop_end', $productsQuery );
					do_action( 'wps_before_products_pagination', $productsQuery );

					if (isset($args['paged']) && $args['paged']) {
						do_action( 'wps_products_pagination', $productsQuery );
					}

					do_action( 'wps_after_products_pagination', $productsQuery );

				} else {

					do_action( 'wps_products_no_results', $args );

				}

				do_action( 'wps_products_after', $productsQuery );

			}

		}





		/*

		Collections Display Wrapper
		TODO: Combine with wps_products_display?

		Fires the wps_clauses_mod during WP_Query

		*/
			public function wps_collections_display($args, $customArgs) {

			if (!is_admin()) {

				$Utils = new Utils();

				$args['context'] = 'wps_collections_query';

				if (is_single()) {
					$args['is_single'] = true;

				} else {
					$args['is_single'] = false;

				}

				$collectionsQueryHash = md5(serialize($args));

				/*

				Here we're caching an entire WP_Query response by hashing the
				argument array. We can safely assume that a given set of args
				will always produce the same list of products assuming the
				product data doesn't change. Therefore it's important that we clear
				this cache whenever a product is updated, created, or deleted.

				*/
				if (get_transient('wps_collections_query_hash_cache_' . $collectionsQueryHash)) {
					$collectionsQuery = get_transient('wps_collections_query_hash_cache_' . $collectionsQueryHash);

				} else {

					$collectionsQuery = new \WP_Query($args);
					set_transient('wps_collections_query_hash_cache_' . $collectionsQueryHash, $collectionsQuery);

				}

				if (Utils::wps_is_manually_sorted($args)) {
					$collections = Utils::wps_manually_sort_posts_by_title($args['custom']['titles'], $collectionsQuery->posts);


				} else {
					$collections = $collectionsQuery->posts;

				}


				// Adding feature imaged to object
				foreach ($collections as $collection) {
					$collection->feat_image = $Utils->get_feat_image_by_id($collection->post_id);
				}


				/*

				Now that we've queried both collections tables, we can combine them
				into a single data set to loop through

				*/
				do_action( 'wps_collections_before', $collections );
				do_action( 'wps_collections_header', $collections );

				if (count($collections) > 0) {

					do_action( 'wps_collections_loop_start', $collections );

					foreach($collections as $collection) {

						do_action( 'wps_collections_item_start', $collection, $args, $customArgs );
						do_action( 'wps_collections_item', $collection, $args );
						do_action( 'wps_collections_item_end', $collection );

					}

					do_action( 'wps_collections_loop_end', $collections );
					do_action( 'wps_before_collections_pagination', $collections );

					if ( isset($args['paged']) && $args['paged']) {
						do_action( 'wps_collections_pagination', $collections );
					}

					do_action( 'wps_after_collections_pagination', $collections );


				} else {
					do_action( 'wps_collections_no_results', $args );

				}

				do_action( 'wps_collections_after', $collections );

			}

		}


		/*

		Collections Loop - Before Collections Loop

		*/
		public function wps_collections_loop_start() {
			return include($this->config->plugin_path . 'public/partials/collections/loop/loop-start.php');
		}


		/*

		Collections Loop - After Collections Loop

		*/
		public function wps_collections_loop_end() {
			return include($this->config->plugin_path . 'public/partials/collections/loop/loop-end.php');
		}


		/*

		Collections Loop - Products List Opening Tag

		*/
		public function wps_collections_item_start($collection, $args, $customArgs) {
			return include($this->config->plugin_path . 'public/partials/collections/loop/item-start.php');
		}


		/*

		Collections Loop - Products List Closing Tag

		*/
		public function wps_collections_item_end($collection) {
			return include($this->config->plugin_path . 'public/partials/collections/loop/item-end.php');
		}


		/*

		Collections Loop - Product

		*/
		public function wps_collections_item($collection) {
			return include($this->config->plugin_path . 'public/partials/collections/loop/item.php');
		}


		/*

		Collections Loop - Before Product

		*/
		public function wps_collections_item_before($collection) {
			return include($this->config->plugin_path . 'public/partials/collections/loop/item-link-start.php');
		}


		/*

		Collections Loop - After Product

		*/
		public function wps_collections_item_after($collection) {
			return include($this->config->plugin_path . 'public/partials/collections/loop/item-link-end.php');
		}


		/*

		Collections Loop - Image

		*/
		public function wps_collections_img($collection) {
			return include($this->config->plugin_path . 'public/partials/collections/loop/item-img.php');
		}


		/*

		Collections Loop - Title

		*/
		public function wps_collections_title($collection) {
			return include($this->config->plugin_path . 'public/partials/collections/loop/item-title.php');
		}


		/*

		Collections Loop - No Results

		*/
		public function wps_collections_no_results($args) {
			return include($this->config->plugin_path . 'public/partials/collections/loop/no-results.php');
		}


		/*

		Collections Loop - Before

		*/
		public function wps_collections_header($collections) {
			return include($this->config->plugin_path . 'public/partials/collections/loop/header.php');
		}


		/*

		Collections Loop - After

		*/
		public function wps_collections_after($collections) {
			echo 'after collections loop';
		}


		/*

		Related Products Config

		*/
		public function wps_products_related_args($defaultArgs, $product) {

			return array(
				'post_type' => $product->post_type,
        'post_status' => 'publish',
        'posts_per_page' => apply_filters('wps_products_related_args_posts_per_page', 4),
				'orderby'   => apply_filters('wps_products_related_args_orderby', 'rand'),
        'paged' => false,
				'post__not_in' => array($product->ID),
				'wps_related_products' => true,
				'wps_related_products_count' => apply_filters('wps_products_related_args_posts_per_page', 4)
			);

		}

		public function wps_products_related_args_posts_per_page($posts_per_page) {
			return $posts_per_page;
		}

		public function wps_products_related_args_orderby($orderby) {
			return $orderby;
		}




		// public function wps_collections_heading_class($collections) {
		// 	return 'SEE';
		// }
		//
		// public function wps_collections_heading($collections) {
		// 	return 'Collections';
		// }


		/*

		Main Collections Config

		*/
		public function wps_collections_args($shortcodeArgs) {

			$DB_Settings_General = new Settings_General();
			$settingsNumPosts = $DB_Settings_General->get_num_posts();

			$paged = get_query_var('paged') ? get_query_var('paged') : 1;

			if( !empty($shortcodeArgs) ) {
				$shortcodeArgs['paged'] = $paged;
				return $shortcodeArgs;

			} else {

				return array(
					'post_type' => 'wps_collections',
					'post_status' => 'publish',
					'posts_per_page' => apply_filters('wps_collections_args_posts_per_page', $settingsNumPosts),
					'orderby'   => apply_filters('wps_collections_args_orderby', 'desc'),
					'paged' => apply_filters('wps_collections_args_paged', $paged)
				);

			}

		}


		/*

		Main Products Config

		*/
		public function wps_products_args($shortcodeArgs) {

			$DB_Settings_General = new Settings_General();
			$settingsNumPosts = $DB_Settings_General->get_num_posts();

			$paged = get_query_var('paged') ? get_query_var('paged') : 1;

			if( !empty($shortcodeArgs) ) {
				$shortcodeArgs['paged'] = $paged;
				return $shortcodeArgs;

			} else {

				return array(
					'post_type' => 'wps_products',
					'post_status' => 'publish',
					'posts_per_page' => apply_filters('wps_products_args_posts_per_page', $settingsNumPosts),
					'orderby'   => apply_filters('wps_products_args_orderby', 'desc'),
					'paged' => apply_filters('wps_products_args_paged', $paged)
				);

			}

		}






		/*

		Need to get pagination to work

		*/
		public function wps_content_pre_loop($query) {

			if (isset($query->query['context']) && isset($query->query['post_type'])) {

				if ($query->query['post_type'] === 'wps_products' || $query->query['post_type'] === 'wps_collections') {

					$DB_Settings_General = new Settings_General();

					$query->set('posts_per_page', $DB_Settings_General->get_num_posts());

				}

			}

			return $query;

		}



		/*

		Hooks: Products

		*/
		public function wps_products_args_posts_per_page($posts_per_page) {
			return $posts_per_page;
		}

		public function wps_products_args_orderby($orderby) {
			return $orderby;
		}

		public function wps_products_args_paged($paged) {
			return $paged;
		}

		public function wps_product_single_notice_inline($product) {
			return include($this->config->plugin_path . "public/partials/products/single/notice-inline.php");
		}

		public function wps_product_single_button_add_to_cart($product) {
			return include($this->config->plugin_path . "public/partials/products/single/button-add-to-cart.php");
		}

		public function wps_product_single_actions_group_start($product) {
			return include($this->config->plugin_path . "public/partials/products/single/action-groups-start.php");
		}

		public function wps_product_single_content($product) {
			return include($this->config->plugin_path . "public/partials/products/single/content.php");
		}

		public function wps_product_single_header($product) {
			return include($this->config->plugin_path . "public/partials/products/single/header.php");
		}

		public function wps_product_single_header_before($product) {
			return include($this->config->plugin_path . "public/partials/products/single/header-before.php");
		}

		public function wps_product_single_header_after($product) {
			return include($this->config->plugin_path . "public/partials/products/single/header-after.php");
		}

		public function wps_product_single_header_price($product) {
			return include($this->config->plugin_path . "public/partials/products/single/header-price.php");
		}

		public function wps_product_single_header_price_before($product) {
			return include($this->config->plugin_path . "public/partials/products/single/header-price-before.php");
		}

		public function wps_product_single_header_price_after($product) {
			return include($this->config->plugin_path . "public/partials/products/single/header-price-after.php");
		}



		public function wps_product_single_quantity($product) {
			return include($this->config->plugin_path . "public/partials/products/single/quantity.php");
		}

		public function wps_product_single_imgs($product) {
			return include($this->config->plugin_path . "public/partials/products/single/imgs.php");
		}

		public function wps_product_single_options($product) {
			return include($this->config->plugin_path . "public/partials/products/single/options.php");
		}

		public function wps_product_single_meta_start($product) {
			return include($this->config->plugin_path . "public/partials/products/single/meta-start.php");
		}

		public function wps_product_single_meta_end($product) {
			return include($this->config->plugin_path . "public/partials/products/single/meta-end.php");
		}

		public function wps_product_single_info_start($product) {
			return include($this->config->plugin_path . "public/partials/products/single/info-start.php");
		}

		public function wps_product_single_info_end($product) {
			return include($this->config->plugin_path . "public/partials/products/single/info-end.php");
		}

		public function wps_product_single_gallery_start($product) {
			return include($this->config->plugin_path . "public/partials/products/single/gallery-start.php");
		}

		public function wps_product_single_gallery_end($product) {
			return include($this->config->plugin_path . "public/partials/products/single/gallery-end.php");
		}

		public function wps_product_single_start($product) {
			return include($this->config->plugin_path . "public/partials/products/single/start.php");
		}

		public function wps_product_single_end($product) {
			return include($this->config->plugin_path . "public/partials/products/single/end.php");
		}


		// public function wps_products_title_class() {
		// 	return 'hey';
		// }
		//
		// public function wps_collections_title_class() {
		// 	return 'yo';
		// }
		// public function wps_collections_img_class() {
		// 	return 'ppppp';
		// }
		//
		// public function wps_products_img_class() {
		// 	return '11111';
		// }
		//
		// public function wps_collections_link_class() {
		// 	return 'hhh';
		// }
		//
		// public function wps_products_link_class() {
		// 	return 'ooo';
		// }
		//
		// public function wps_product_class() {
		// 	return 'weee';
		// }
		// public function wps_products_class() {
		// 	return 'weeesss';
		// }
		//
		// public function wps_collections_class() {
		// 	return 'yyyy';
		// }
		//
		// public function wps_collection_class() {
		// 	return 'yyyyzzz';
		// }


		// public function wps_products_heading_class() {
		// 	return 'sdfsdf';
		// }



		/*

		Hooks: Collections

		*/
		public function wps_collection_single_start($collection) {
			return include($this->config->plugin_path . "public/partials/collections/single/start.php");
		}

		public function wps_collection_single_header($collection) {
			return include($this->config->plugin_path . "public/partials/collections/single/header.php");
		}

		public function wps_collection_single_img($collection) {
			return include($this->config->plugin_path . "public/partials/collections/single/img.php");
		}

		public function wps_collection_single_content($collection) {
			return include($this->config->plugin_path . "public/partials/collections/single/content.php");
		}

		public function wps_collection_single_products($collection, $products) {
			return include($this->config->plugin_path . "public/partials/collections/single/products.php");
		}

		public function wps_collection_single_end($collection) {
			return include($this->config->plugin_path . "public/partials/collections/single/end.php");
		}

		public function wps_collection_single_products_before($collection, $products) {
			return include($this->config->plugin_path . "public/partials/collections/single/products-before.php");
		}

		public function wps_collection_single_product($product) {
			return include($this->config->plugin_path . "public/partials/collections/single/product.php");
		}







		public function wps_collection_single_heading_before($collection) {
			echo 'before';
		}

		public function wps_collection_single_heading_after($collection) {
			echo 'after';
		}

		public function wps_collection_single_sidebar() {

			$sidebar = apply_filters('wps_collection_single_show_sidebar', false);

			if ($sidebar) {
				get_sidebar('wps');
			}

		}

		public function wps_collections_sidebar() {

			$sidebar = apply_filters('wps_collections_show_sidebar', false);

			if ($sidebar) {
				get_sidebar('wps');
			}

		}





		public function wps_product_single_thumbs_start() {
			return include($this->config->plugin_path . 'public/partials/products/single/thumbs-start.php');
		}

		public function wps_product_single_thumbs_end() {
			return include($this->config->plugin_path . 'public/partials/products/single/thumbs-end.php');
		}

		public function wps_product_single_thumbs_class() {
			return;
		}

		public function wps_product_single_sidebar() {

			$sidebar = apply_filters('wps_product_single_show_sidebar', false);

			if ($sidebar) {
				get_sidebar('wps');
			}

		}




		public function wps_product_single_price($default, $priceFirst, $priceLast, $product) {

			$finalPrice = '';

			if ($priceFirst !== $priceLast) {
				$defaultPrice = apply_filters('wps_product_single_price_multi_from', '<small class="wps-product-from-price">From: </small>') . apply_filters('wps_product_single_price_multi_first', $priceFirst) . apply_filters('wps_product_single_price_multi_separator', ' <span class="wps-product-from-price-separator">-</span> ') . apply_filters('wps_product_single_price_multi_last', $priceLast);

				$finalPrice = apply_filters('wps_product_single_price_multi', $defaultPrice, $priceFirst, $priceLast, $product);

			} else {

				$finalPrice = apply_filters('wps_product_single_price_one', $priceFirst, $priceFirst, $product);

			}

			return $finalPrice;

		}




		/*

		Product pricing fitlers. Need to return defaults.

		*/
		public function wps_products_price_multi($defaultPrice, $priceFirst, $priceLast, $product) {
			return $defaultPrice;
		}

		public function wps_products_price_one($defaultPrice, $product) {
			return $defaultPrice;
		}

		public function wps_product_single_price_multi($defaultPrice, $priceFirst, $priceLast, $product) {
			return $defaultPrice;
		}

		public function wps_product_single_price_one($defaultPrice, $finalPrice, $product) {
			return $defaultPrice;
		}





		public function wps_products_sidebar() {

			$sidebar = apply_filters('wps_products_show_sidebar', false);

			if ($sidebar) {
				get_sidebar('wps');
			}

		}







		public function wps_collection_single_products_heading_class() {
			return '';
		}

		public function wps_collection_single_products_heading() {
			return 'Products';
		}


		public function wps_cart_before() {
			echo 'Cart before';
		}

		public function wps_cart_after() {
			echo '<div>Cart after</div>';
		}

		public function wps_cart_title_text() {
			return 'Shopping cart';
		}

		public function wps_cart_close_icon() {
			return '&times;';
		}

		public function wps_cart_total_text() {
			return 'Total';
		}

		public function wps_cart_checkout_text() {
			return 'Checkout';
		}

		public function wps_cart_shipping_text() {
			return 'Hello hello hello';
		}

		public function wps_cart_item_class() {
			return 'Cddddheckout';
		}

		public function wps_cart_class() {
			return 'hey';
		}

		public function wps_cart_counter_class() {
			return 'counterr';
		}

		public function wps_cart_icon_class() {
			return 'iconn';
		}

		public function wps_cart_btn_class() {
			return 'btttnt';
		}

		public function wps_cart_counter() {
			return include($this->config->plugin_path . "public/partials/cart/cart-counter.php");
		}

		public function wps_cart_icon() {
			return include($this->config->plugin_path . "public/partials/cart/cart-icon.php");
		}





    /*

    Initialization

    */
		public function init() {




		}

	}

}
