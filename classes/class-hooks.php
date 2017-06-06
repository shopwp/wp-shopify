<?php

namespace WPS;

use WPS\Utils;
use WPS\DB;
use WPS\DB\Products;
use WPS\DB\Variants;
use WPS\DB\Collections_Smart;
use WPS\DB\Collections_Custom;

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
		public static function instance() {

			if (is_null(self::$instantiated)) {
				self::$instantiated = new self();
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
		public function wps_products_loop_end() {
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
		public function wps_products_item_before($product, $settings) {
			return include($this->config->plugin_path . 'public/partials/products/loop/item-link-start.php');
		}


    /*

    Products Loop - After Product

    */
		public function wps_products_item_after($product) {
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

    Products Loop - Price

    */
		public function wps_products_price($product) {
			return include($this->config->plugin_path . 'public/partials/products/loop/item-price.php');
		}


		/*

		Products Loop - Before

		*/
		public function wps_products_before($query) {
			return include($this->config->plugin_path . 'public/partials/products/loop/before.php');
		}


		/*

		Products Loop - After

		*/
		public function wps_products_after($args) {
			echo 'Afffterr';
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

				// error_log('Products Query');
				// error_log(print_r($query, true));

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

			} else if ($query->get('context') === 'wps_collections_custom_query') {

				$DB_Collections_Custom = new Collections_Custom();
				$table_collections_custom = $DB_Collections_Custom->get_table_name();

				$sql .= " INNER JOIN " . $table_collections_custom . " ON " .
					 $wpdb->posts . ".ID = " . $table_collections_custom .
					 ".post_id ";

			} else if ($query->get('context') === 'wps_collections_smart_query') {

				$DB_Collections_Smart = new Collections_Smart();
				$table_collections_smart = $DB_Collections_Smart->get_table_name();

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

				} else if ($query->get('context') === 'wps_collections_custom_query') {

					// If using Shortcode ...
					if ($query->get('custom')) {

						$clauses = Utils::construct_clauses_from_collections_custom_shortcode($query->get('custom'), $query);

					} else {

						$clauses = $DB_Collections_Custom->get_default_query();

					}

				} else if ($query->get('context') === 'wps_collections_smart_query') {

					// If using Shortcode ...
					if ($query->get('custom')) {

						$clauses = Utils::construct_clauses_from_collections_smart_shortcode($query->get('custom'), $query);

					} else {

						$clauses = $DB_Collections_Smart->get_default_query();

					}

				}


				if (empty($clauses['limits'])) {
					// error_log('LIMIT NOT SET');

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

					// error_log(print_r($clauses, true));

				} else {
					// error_log('LIMIT ALREADY SET');
					// error_log(print_r($clauses, true));
				}

				// error_log('clauses');
				// error_log(print_r($clauses, true));

			}

			return $clauses;

		}


		/*

		wps_products_select_mod

		*/
		public function wps_products_select_mod($sql, $query) {

			global $wpdb;

			$DB_Products = new Products();
			$DB_Variants = new Variants();

			$DB_Collections_Smart = new Collections_Smart();
			$DB_Collections_Custom = new Collections_Custom();

	    $table_products = $DB_Products->get_table_name();
			$table_variants = $DB_Variants->get_table_name();

			$table_collections_smart = $DB_Collections_Smart->get_table_name();
			$table_collections_custom = $DB_Collections_Custom->get_table_name();

			if ($query->get('context') === 'wps_products_query') {
				$sql .= ", " . $table_products . ".*";
				$sql .= ", " . $table_variants . ".price";

			} else if ($query->get('context') === 'wps_collections_custom_query') {
				$sql .= ", " . $table_collections_custom . ".*";

			} else if ($query->get('context') === 'wps_collections_smart_query') {
				$sql .= ", " . $table_collections_smart . ".*";

			}

			return $sql;

		}



		/*

		Products Display Wrapper

		*/
		public function wps_products_display($args, $customArgs) {

			global $wpdb;

			$settings = $this->config->wps_get_settings_general();

			// error_log('=-===--=-=-');
			// error_log(print_r($args, true));

			$args['context'] = 'wps_products_query';

			if (is_single()) {
				$args['is_single'] = true;

			} else {
				$args['is_single'] = false;

			}

			/*

			Products needs to be an array of CPTs sorted by the custom SQL
			query that we make based on the arguments passed via shortcode.


			*/
			$products = new \WP_Query($args);

			do_action( 'wps_products_before', $products );
			do_action( 'wps_products_loop_start', $products );

			foreach($products->posts as $product) {

				do_action( 'wps_products_item_start', $product, $args, $customArgs );
				do_action( 'wps_products_item', $product, $args, $settings );
				do_action( 'wps_products_item_end', $product );

			}

			do_action( 'wps_products_loop_end' );
			do_action( 'wps_before_products_pagination' );

			if (isset($args['paged']) && $args['paged']) {
				do_action( 'wps_products_pagination', $products );
			}

			do_action( 'wps_after_products_pagination' );

			// wp_reset_postdata();
			// wp_reset_query();

			do_action( 'wps_products_after' );

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
		public function wps_collections_before($collections) {
			return include($this->config->plugin_path . 'public/partials/collections/loop/before.php');
		}


		/*

		Collections Loop - After

		*/
		public function wps_collections_after($collections) {
			echo 'after collections loop';
		}


		/*

		Collections Display Wrapper
		TODO: Combine with wps_products_display?

		Fires the wps_clauses_mod during WP_Query

		*/
		public function wps_collections_display($args, $customArgs) {

			$collections = array();

			// Fires the wps_clauses_mod function
			$args['context'] = 'wps_collections_custom_query';
			$collections_custom = new \WP_Query($args);

			// Fires the wps_clauses_mod function
			$args['context'] = 'wps_collections_smart_query';
			$collections_smart = new \WP_Query($args);

			if (is_single()) {
				$args['is_single'] = true;

			} else {
				$args['is_single'] = false;

			}

			/*

			Now that we've queried both collections tables, we can combine them
			into a single data set to loop through

			*/
			$collections = array_merge( $collections_smart->posts, $collections_custom->posts );

			do_action( 'wps_collections_before', $collections );

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

				wp_reset_postdata();

			} else {
				do_action( 'wps_collections_no_results', $args );

			}

			do_action( 'wps_collections_after', $collections );

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




		public function wps_collections_heading_class($collections) {
			return 'SEE';
		}

		public function wps_collections_heading($collections) {
			return 'Collections';
		}


		/*

		Main Collections Config

		*/
		public function wps_collections_args($shortcodeArgs) {

			if( !empty($shortcodeArgs) ) {
				return $shortcodeArgs;

			} else {
				$paged = get_query_var('paged') ? get_query_var('paged') : 1;

				return array(
					'post_type' => 'wps_collections',
					'post_status' => 'publish',
					'posts_per_page' => apply_filters('wps_products_args_posts_per_page', -1),
					'orderby'   => apply_filters('wps_products_args_orderby', 'desc'),
					'paged' => apply_filters('wps_products_args_paged', $paged)
				);
			}

		}


		/*

		Main Products Config

		*/
		public function wps_products_args($shortcodeArgs) {

			// error_log('$shortcodeArgs');
			// error_log(print_r($shortcodeArgs));

			if( !empty($shortcodeArgs) ) {
				return $shortcodeArgs;

			} else {
				$paged = get_query_var('paged') ? get_query_var('paged') : 1;

				return array(
					'post_type' => 'wps_products',
					'post_status' => 'publish',
					'posts_per_page' => apply_filters('wps_products_args_posts_per_page', get_option( 'posts_per_page' )),
					'orderby'   => apply_filters('wps_products_args_orderby', 'desc'),
					'paged' => apply_filters('wps_products_args_paged', $paged)
				);

			}

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





		/*

		Hooks: Collections

		*/
		public function wps_collections_single_start($collection) {
			return include($this->config->plugin_path . "public/partials/collections/single/start.php");
		}

		public function wps_collections_single_header($collection) {
			return include($this->config->plugin_path . "public/partials/collections/single/header.php");
		}

		public function wps_collections_single_img($collection) {
			return include($this->config->plugin_path . "public/partials/collections/single/img.php");
		}

		public function wps_collections_single_content($collection) {
			return include($this->config->plugin_path . "public/partials/collections/single/content.php");
		}

		public function wps_collections_single_products($collection, $products) {
			return include($this->config->plugin_path . "public/partials/collections/single/products.php");
		}

		public function wps_collections_single_end($collection) {
			return include($this->config->plugin_path . "public/partials/collections/single/end.php");
		}

		public function wps_collections_single_products_before($collection, $products) {
			return include($this->config->plugin_path . "public/partials/collections/single/products-before.php");
		}

		public function wps_collections_single_heading_before($collection) {
			echo 'before';
		}

		public function wps_collections_single_heading_after($collection) {
			echo 'after';
		}





		public function wps_collections_single_products_heading_class() {
			return '';
		}

		public function wps_collections_single_products_heading() {
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

			/*

			Products Pagination

			*/
      add_filter( 'wps_products_pagination_start', array($this, 'wps_products_pagination_start') );
			add_filter( 'wps_products_pagination_end', array($this, 'wps_products_pagination_end') );
			add_filter( 'wps_products_pagination_first_page_text', array($this, 'wps_products_pagination_first_page_text') );
			add_filter( 'wps_products_pagination_next_link_text', array($this, 'wps_products_pagination_next_link_text') );
			add_filter( 'wps_products_pagination_prev_link_text', array($this, 'wps_products_pagination_prev_link_text') );
			add_filter( 'wps_products_pagination_range', array($this, 'wps_products_pagination_range') );
			add_filter( 'wps_products_pagination_show_as_prev_next', array($this, 'wps_products_pagination_show_as_prev_next') );
			add_filter( 'wps_products_pagination_prev_page_text', array($this, 'wps_products_pagination_prev_page_text') );
			add_filter( 'wps_products_pagination_next_page_text', array($this, 'wps_products_pagination_next_page_text') );

			/*

			Products Args (Default + Custom)

			*/
			add_filter( 'wps_products_args', array($this, 'wps_products_args') );
			add_filter( 'wps_products_args_posts_per_page', array($this, 'wps_products_args_posts_per_page') );
			add_filter( 'wps_products_args_orderby', array($this, 'wps_products_args_orderby') );
			add_filter( 'wps_products_args_paged', array($this, 'wps_products_args_paged') );
			add_filter( 'wps_products_custom_args', array($this, 'wps_products_custom_args'));
			add_filter( 'wps_products_custom_args_items_per_row', array($this, 'wps_products_custom_args_items_per_row'));






			/*

			Collections Args (Default + Custom)

			*/
			add_filter( 'wps_collections_args', array($this, 'wps_collections_args') );
			add_filter( 'wps_collections_custom_args', array($this, 'wps_collections_custom_args'));
			add_filter( 'wps_collections_custom_args_items_per_row', array($this, 'wps_collections_custom_args_items_per_row'));


			/*

			Collections Loop

			*/
			add_action( 'wps_collections_before', array($this, 'wps_collections_before') );
			add_action( 'wps_collections_loop_start', array($this, 'wps_collections_loop_start') );
			add_action( 'wps_collections_loop_end', array($this, 'wps_collections_loop_end') );
			add_action( 'wps_collections_item_start', array($this, 'wps_collections_item_start'), 9, 3 );
			add_action( 'wps_collections_item_end', array($this, 'wps_collections_item_end') );
			add_action( 'wps_collections_item', array($this, 'wps_collections_item') );
			add_action( 'wps_collections_item_before', array($this, 'wps_collections_item_before') );
			add_action( 'wps_collections_item_after', array($this, 'wps_collections_item_after') );
			add_action( 'wps_collections_img', array($this, 'wps_collections_img') );
			add_action( 'wps_collections_title', array($this, 'wps_collections_title') );
			add_action( 'wps_collections_no_results', array($this, 'wps_collections_no_results') );
			// add_action( 'wps_collections_after', array($this, 'wps_collections_after') );





			// add_filter( 'wps_collections_heading_class', array($this, 'wps_collections_heading_class') );
			// add_filter( 'wps_collections_heading', array($this, 'wps_collections_heading') );



			/*

			Collections Single

			*/
			add_action('wps_collections_single_start', array($this, 'wps_collections_single_start'));
			add_action('wps_collections_single_header', array($this, 'wps_collections_single_header'));
			add_action('wps_collections_single_img', array($this, 'wps_collections_single_img'));
			add_action('wps_collections_single_content', array($this, 'wps_collections_single_content'));
			add_action('wps_collections_single_products', array($this, 'wps_collections_single_products'),  9, 3 );
			add_action('wps_collections_single_end', array($this, 'wps_collections_single_end'));


			// add_action('wps_collections_single_heading_before', array($this, 'wps_collections_single_heading_before'));
			// add_action('wps_collections_single_heading_after', array($this, 'wps_collections_single_heading_after'));


			add_action('wps_collections_single_products_before', array($this, 'wps_collections_single_products_before'), 9, 3);
			add_filter( 'wps_collections_single_products_heading_class', array($this, 'wps_collections_single_products_heading_class'));
			add_filter( 'wps_collections_single_products_heading', array($this, 'wps_collections_single_products_heading'));



			/*

			Products Loop

			*/
			add_action( 'wps_products_before', array($this, 'wps_products_before') );
      add_action( 'wps_products_loop_start', array($this, 'wps_products_loop_start') );
      add_action( 'wps_products_loop_end', array($this, 'wps_products_loop_end') );
      add_action( 'wps_products_item_start', array($this, 'wps_products_item_start'), 9, 3 );
      add_action( 'wps_products_item_end', array($this, 'wps_products_item_end') );
      add_action( 'wps_products_item', array($this, 'wps_products_item'), 9, 3 );
      add_action( 'wps_products_item_before', array($this, 'wps_products_item_before'), 9, 2 );
      add_action( 'wps_products_item_after', array($this, 'wps_products_item_after') );
      add_action( 'wps_products_img', array($this, 'wps_products_img') );
      add_action( 'wps_products_title', array($this, 'wps_products_title') );
      add_action( 'wps_products_price', array($this, 'wps_products_price') );

			add_action( 'wps_products_pagination', array($this, 'wps_products_pagination') );

			// add_action( 'wps_products_after', array($this, 'wps_products_after') );




			// add_filter( 'wps_collections_title_class', array($this, 'wps_collections_title_class') );
			// add_filter( 'wps_products_title_class', array($this, 'wps_products_title_class') );
			// add_filter( 'wps_collections_img_class', array($this, 'wps_collections_img_class') );
			// add_filter( 'wps_products_img_class', array($this, 'wps_products_img_class') );
			// add_filter( 'wps_collections_link_class', array($this, 'wps_collections_link_class') );
			// add_filter( 'wps_products_link_class', array($this, 'wps_products_link_class') );
			// add_filter( 'wps_product_class', array($this, 'wps_product_class') );
			// add_filter( 'wps_products_class', array($this, 'wps_products_class') );
			// add_filter( 'wps_collections_class', array($this, 'wps_collections_class') );
			// add_filter( 'wps_collection_class', array($this, 'wps_collection_class') );







			/*

			General

			*/
			add_action( 'wps_products_display', array($this, 'wps_products_display'), 9, 2 );
			add_action( 'wps_collections_display', array($this, 'wps_collections_display'), 9, 2 );


			// add_filter( 'posts_join', array($this, 'wps_products_join'), 9, 2 );
			// add_filter( 'posts_fields', array($this, 'wps_products_select_mod'), 9, 2 );

			add_filter( 'posts_clauses', array($this, 'wps_clauses_mod'), 9, 2 );





			/*

			Products Related

			*/
			// add_action( 'wps_products_related_before', array($this, 'wps_products_related_before') );
			// add_action( 'wps_products_related_after', array($this, 'wps_products_related_after') );
			// add_action( 'wps_products_related_heading_before', array($this, 'wps_products_related_heading_before') );
			// add_filter( 'wps_products_related_heading', array($this, 'wps_products_related_heading') );
			// add_action( 'wps_products_related_heading_end_after', array($this, 'wps_products_related_heading_end_after') );

			add_filter( 'wps_products_related_args', array($this, 'wps_products_related_args'), 9, 2);
			add_filter( 'wps_products_related_args_posts_per_page', array($this, 'wps_products_related_args_posts_per_page') );
			add_filter( 'wps_products_related_args_orderby', array($this, 'wps_products_related_args_orderby') );
			add_filter( 'wps_products_related_custom_args', array($this, 'wps_products_related_custom_args'));
			add_filter( 'wps_products_related_custom_items_per_row', array($this, 'wps_products_related_custom_items_per_row'));

			add_action( 'wps_products_related_start', array($this, 'wps_products_related_start'));
			add_action( 'wps_products_related_end', array($this, 'wps_products_related_end'));

			add_action( 'wps_products_related_heading_start', array($this, 'wps_products_related_heading_start'));
			add_action( 'wps_products_related_heading_end', array($this, 'wps_products_related_heading_end'));

			/*

			Product Single

			*/
			add_action('wps_product_single_after', array($this, 'wps_related_products'));
			add_action('wps_product_single_notice_inline', array($this, 'wps_product_single_notice_inline'));
			add_action('wps_product_single_button_add_to_cart', array($this, 'wps_product_single_button_add_to_cart'));
			add_action('wps_product_single_actions_group_start', array($this, 'wps_product_single_actions_group_start'));
			add_action('wps_product_single_content', array($this, 'wps_product_single_content'));
			add_action('wps_product_single_header', array($this, 'wps_product_single_header'));
			add_action('wps_product_single_quantity', array($this, 'wps_product_single_quantity'));
			add_action('wps_product_single_imgs', array($this, 'wps_product_single_imgs'));
			add_action('wps_product_single_options', array($this, 'wps_product_single_options'));
			add_action('wps_product_single_meta_start', array($this, 'wps_product_single_meta_start'));
			add_action('wps_product_single_meta_end', array($this, 'wps_product_single_meta_end'));
			add_action('wps_product_single_info_start', array($this, 'wps_product_single_info_start'));
			add_action('wps_product_single_info_end', array($this, 'wps_product_single_info_end'));
			add_action('wps_product_single_gallery_start', array($this, 'wps_product_single_gallery_start'));
			add_action('wps_product_single_gallery_end', array($this, 'wps_product_single_gallery_end'));
			add_action('wps_product_single_start', array($this, 'wps_product_single_start'));
			add_action('wps_product_single_end', array($this, 'wps_product_single_end'));



			/*

			Cart

			*/
			add_action('wps_cart_icon', array($this, 'wps_cart_icon'));
			add_action('wps_cart_counter', array($this, 'wps_cart_counter'));



			// add_filter( 'wps_cart_btn_class', array($this, 'wps_cart_btn_class'));
			// add_filter( 'wps_cart_icon_class', array($this, 'wps_cart_icon_class'));
			// add_filter( 'wps_cart_counter_class', array($this, 'wps_cart_counter_class'));


			// add_action('wps_cart_before', array($this, 'wps_cart_before'));
			// add_action('wps_cart_after', array($this, 'wps_cart_after'));
			// add_filter( 'wps_cart_item_class', array($this, 'wps_cart_item_class'));

			// add_filter( 'wps_cart_title_text', array($this, 'wps_cart_title_text'));
			// add_filter( 'wps_cart_total_text', array($this, 'wps_cart_total_text'));
			//
			// add_filter( 'wps_cart_shipping_text', array($this, 'wps_cart_shipping_text'));
			// add_filter( 'wps_cart_checkout_text', array($this, 'wps_cart_checkout_text'));

			// add_filter( 'wps_cart_class', array($this, 'wps_cart_class'));
			// add_filter( 'wps_cart_close_icon', array($this, 'wps_cart_close_icon'));


		}

	}

}
