<?php

namespace WPS;

use WPS\Utils;
use WPS\Transients;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Hooks')) {

	class Hooks {

		private $Utils;
		private $DB_Settings_General;
		private $DB_Shop;
		private $Templates;
		private $Async_Processing_Database;
		private $Pagination;
		private $DB_Settings_Syncing;
		private $DB_Settings_License;
		private $Activator;


    /*

    Initialize the class and set its properties.

    */
    public function __construct($Utils, $DB_Settings_General, $DB_Shop, $Templates, $Async_Processing_Database, $Pagination, $DB_Settings_Syncing, $DB_Settings_License, $Activator) {

			$this->Utils 												= $Utils;
			$this->DB_Settings_General 					= $DB_Settings_General;
			$this->DB_Shop 											= $DB_Shop;
			$this->Templates 										= $Templates;
			$this->Async_Processing_Database 		= $Async_Processing_Database;
			$this->Pagination										=	$Pagination;
			$this->DB_Settings_Syncing					=	$DB_Settings_Syncing;
			$this->DB_Settings_License					=	$DB_Settings_License;
			$this->Activator										=	$Activator;

    }


    /*

    Products Pagination
		TODO: Combine with wps_collections_pagination

    */
		public function wps_products_pagination($productsQuery) {

			$args = [
				'query' => $productsQuery
			];

			// If user turns pagination off via WPS settings just exit
			if ($this->DB_Settings_General->hide_pagination()) {
				return;
			}

			// If user turns pagination off via shortcode just exit
			if (isset($args['query']->query['custom']['pagination'])) {
				return;
			}

			if (isset($args['query']->query['paged']) && $args['query']->query['paged'] ) {
				echo $this->Pagination->get_paginated_numbers($args);
			}

		}


		/*

    Collections Pagination

    */
		public function wps_collections_pagination($collectionsQuery) {

			$args = array(
				'query' => $collectionsQuery
			);

			// If user turns pagination off via WPS settings just exit
			if ($this->DB_Settings_General->hide_pagination()) {
				return;
			}

			// If user turns pagination off via shortcode just exit
			if (isset($args['query']->query['custom']['pagination'])) {
				return;
			}

			if (isset($args['query']->query['paged']) && $args['query']->query['paged'] ) {
				echo $this->Pagination->get_paginated_numbers($args);
			}


		}


		/*

		wps_products_custom_args

		*/
		public function wps_products_custom_args($args) {

			return array(
				'items_per_row' => apply_filters('wps_products_custom_args_items_per_row', 3)
			);

		}

		public function wps_collections_custom_args() {

			return array(
				'items_per_row' => apply_filters('wps_collections_custom_args_items_per_row', 3)
			);

		}

		public function wps_products_related_custom_args() {

			return array(
				'items_per_row' => apply_filters('wps_products_related_custom_items_per_row', 4)
			);

		}

		public function wps_products_custom_args_items_per_row($items_per_row) {
			return 3;
		}

		public function wps_collections_custom_args_items_per_row($items_per_row) {
			return 4;
		}

		public function wps_products_related_custom_items_per_row($items_per_row) {
			return 4;
		}

		public function wps_products_pagination_first_page_text() {
			return 'First';
		}

		public function wps_products_pagination_next_link_text() {
			return '';
		}

		public function wps_products_pagination_prev_link_text() {
			return '';
		}

		public function wps_products_pagination_prev_page_text() {
			return '<<';
		}

		public function wps_products_pagination_next_page_text() {
			return '>>';
		}

		public function wps_products_pagination_show_as_prev_next() {
			return false;
		}

		public function wps_products_pagination_range() {
			return 5;
		}

		public function wps_collection_single_heading_before($collection) {
			echo '';
		}

		public function wps_collection_single_heading_after($collection) {
			echo '';
		}

		public function wps_product_single_thumbs_class() {
			return;
		}

		public function wps_products_related_before() {
			echo '';
		}

		public function wps_products_related_after() {
			echo '';
		}

		public function wps_products_related_heading_text() {
			echo 'Related';
		}

		public function wps_products_related_heading_end_after() {
			echo '';
		}

		public function wps_collection_single_products_heading_class() {
			return '';
		}

		public function wps_collections_heading_class($collections) {
			return '';
		}

		public function wps_collections_heading($collections) {
			return '';
		}

		public function wps_products_title_class() {
			return '';
		}

		public function wps_collections_title_class() {
			return '';
		}
		public function wps_collections_img_class() {
			return '';
		}

		public function wps_products_img_class() {
			return '';
		}

		public function wps_collections_link_class() {
			return '';
		}

		public function wps_products_link_class() {
			return '';
		}

		public function wps_product_class() {
			return '';
		}
		public function wps_products_class() {
			return '';
		}

		public function wps_collections_class() {
			return '';
		}

		public function wps_collection_class() {
			return '';
		}

		public function wps_products_heading_class() {
			return '';
		}

		public function wps_collection_single_products_heading() {
			return 'Products';
		}

		public function wps_cart_before() {
			echo '';
		}

		public function wps_cart_after() {
			echo '';
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


		/*

		Related products amount to show

		*/
		public function wps_products_related_args_posts_per_page($posts_per_page) {

			$related_products_amount = $this->DB_Settings_General->related_products_amount();

			if (isset($related_products_amount)) {
				return $related_products_amount;

			} else {
				return $posts_per_page;
			}

		}


		/*

		Related products show

		*/
		public function wps_products_related_show($show) {

			$related_products_show = $this->DB_Settings_General->related_products_show();

			if (isset($related_products_show)) {
				return $related_products_show;

			} else {
				return $show;
			}

		}


		/*

		Related products filter

		*/
		public function wps_products_related_filters($defaultFilters, $product) {

			$related_products_sort = $this->DB_Settings_General->related_products_sort();


			if (!isset($related_products_sort)) {
				return [];
			}

			if (isset($related_products_sort) && $related_products_sort === 'random') {
				return [];
			}


			/*

			If filtering related products by collections...

			*/
			if ($related_products_sort === 'collections') {

				$collectionsNew = [];

				$collectionsNew = array_map(function($collection) {
					return $collection->title;
				}, $product->collections);

				return [
					'collections' => $collectionsNew
				];

			}


			/*

			If filtering related products by tags...

			*/
			if ($related_products_sort === 'tags') {

				return [
					'tags' => $product->details->tags
				];
			}


			/*

			If filtering related products by vendors...

			*/
			if ($related_products_sort === 'vendors') {

				return [
					'vendors' => $product->details->vendor
				];

			}


			/*

			If filtering related products by types...

			*/
			if ($related_products_sort === 'types') {

				return [
					'types' => $product->details->product_type
				];

			}


		}




		public function wps_products_related_args_orderby($orderby) {
			return $orderby;
		}

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

		public function wps_products_args_posts_per_page($posts_per_page) {
			return $posts_per_page;
		}

		public function wps_products_args_orderby($orderby) {
			return $orderby;
		}

		public function wps_products_args_paged($paged) {
			return $paged;
		}


		/*

		Setting: Products link to Shopify

		*/
		public function wps_products_link($wp_shopify_link, $product) {

			if ($this->DB_Settings_General->products_link_to_shopify()) {

				return 'https://' . $this->DB_Shop->domain() . '/products/' . $product->handle;

			} else {
				return $wp_shopify_link;
			}

		}



		/*

		Sidebar: Collections Single

		*/
		public function wps_collection_single_sidebar() {

			if (apply_filters('wps_collection_single_show_sidebar', false)) {
				get_sidebar('wps');
			}

		}


		/*

		Sidebar: Collections

		*/
		public function wps_collections_sidebar() {

			if (apply_filters('wps_collections_show_sidebar', false)) {
				get_sidebar('wps');
			}

		}


		/*

		Sidebar: Products Single

		*/
		public function wps_product_single_sidebar() {

			if (apply_filters('wps_product_single_show_sidebar', false)) {
				get_sidebar('wps');
			}

		}


		/*

		Sidebar: Products

		*/
		public function wps_products_sidebar() {

			if (apply_filters('wps_products_show_sidebar', false)) {
				get_sidebar('wps');
			}

		}


		/*

		Related Products Args

		*/
		public function wps_products_related_args($defaultArgs) {

			global $post;

			return [
				'post_type' 										=> $post->post_type,
        'post_status' 									=> 'publish',

				// Not currently used
        'posts_per_page' 								=> apply_filters('wps_products_related_args_posts_per_page', 4),

				// TODO: Make this an option in the backend
				'orderby'   										=> apply_filters('wps_products_related_args_orderby', 'desc'),
        'paged' 												=> false,
				'post__not_in' 									=> array($post->ID),
				'wps_related_products' 					=> apply_filters('wps_products_related_show', true),

				// Allows for custom filtering of related products
				'custom' 												=> apply_filters('wps_products_related_filters', [], $this->Templates->get_product_data($post->ID)),

				// Allows for customing how many related products show
				'wps_related_products_count' 		=> apply_filters('wps_products_related_args_posts_per_page', 4),

				// Allows for customing how many related products per row
				'wps_related_products_items_per_row' => apply_filters('wps_products_related_args_items_per_row', false)

			];

		}


		/*

		Main Collections
		TODO: Think about combining with wps_products_args

		*/
		public function wps_collections_args($shortcodeData) {

			$settingsNumPosts = $this->DB_Settings_General->get_num_posts();

			$paged = get_query_var('paged') ? get_query_var('paged') : 1;


			if ( empty($shortcodeData->shortcodeArgs) ) {

				return [
					'post_type' 			=> WPS_COLLECTIONS_POST_TYPE_SLUG,
					'post_status' 		=> 'publish',
					'posts_per_page' 	=> apply_filters('wps_collections_args_posts_per_page', $settingsNumPosts),
					'orderby'   			=> apply_filters('wps_collections_args_orderby', 'desc'),
					'paged' 					=> apply_filters('wps_collections_args_paged', $paged)
				];

			} else {

				$shortcodeData->shortcodeArgs['paged'] = $paged;
				return $shortcodeData->shortcodeArgs;

			}

		}


		/*

		Main Products

		*/
		public function wps_products_args($shortcodeData) {

			$settingsNumPosts = $this->DB_Settings_General->get_num_posts();

			$paged = get_query_var('paged') ? get_query_var('paged') : 1;

			if ( empty($shortcodeData->shortcodeArgs) ) {

				return [
					'post_type' => WPS_PRODUCTS_POST_TYPE_SLUG,
					'post_status' => 'publish',
					'posts_per_page' => apply_filters('wps_products_args_posts_per_page', $settingsNumPosts),
					'orderby'   => apply_filters('wps_products_args_orderby', 'desc'),
					'paged' => apply_filters('wps_products_args_paged', $paged)
				];

			} else {

				$shortcodeData->shortcodeArgs['paged'] = $paged;
				return $shortcodeData->shortcodeArgs;

			}

		}





		/*

		Product single price

		*/
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

		Table doesnt exist, need to notify user of that

		*/
		public function show_missing_tables_notice($error) {

			return add_action('admin_notices', function() use($error) { ?>

				<div class="notice wps-notice notice-warning is-dismissible">
					<p><?= Utils::filter_error_messages($error); ?></p>
				</div>

			<?php });

		}



		/*

		Runs when the plugin updates.

		Will only run once since we're updating the plugin verison after everything gets executed.

		TODO: This functions gets executed many times. Even though most of the time it will return
		immeditately, it will still make an unnesssary call to get_current_plugin_version() which
		actually gets the DB. We should figure out a way to avoid this.

		*/
		public function on_plugin_load() {

			$new_version_number = WPS_NEW_PLUGIN_VERSION;
			$current_version_number = $this->DB_Settings_General->get_current_plugin_version();

			// // $new_version_number = '1.2.99';

			// If current version is behind new version
			if (version_compare($current_version_number, $new_version_number, '<')) {

				if (version_compare($current_version_number, '1.2.2', '<')) {

					if ( !Transients::database_migration_needed() ) {
						update_option('wp_shopify_migration_needed', true);
					}

				} else {

					// Only runs when table columns are different
					$this->Async_Processing_Database->sync_table_deltas();

				}

				$this->DB_Settings_General->update_plugin_version($new_version_number);
				Transients::delete_short_term_cache();

			}

		}


		/*

		For later use ... after plugin updates.

		*/
		// public function after_plugin_update($upgrader_object, $options ) {
		//
		// }

		/*

		Hooks

		*/
		public function hooks() {

			// add_action('upgrader_process_complete', [$this, 'after_plugin_update'], 10, 2 );
			add_action('plugins_loaded', [$this, 'on_plugin_load']);

			add_action('wps_products_sidebar', [$this, 'wps_products_sidebar']);
			add_action('wps_product_single_sidebar', [$this, 'wps_product_single_sidebar']);
			add_action('wps_collections_sidebar', [$this, 'wps_collections_sidebar']);
			add_action('wps_collection_single_sidebar', [$this, 'wps_collection_single_sidebar']);
			add_action('wps_collections_pagination', [$this, 'wps_collections_pagination']);
			add_filter('wps_collections_args', [$this, 'wps_collections_args']);
			add_filter('wps_collections_custom_args', [$this, 'wps_collections_custom_args']);
			add_filter('wps_collections_custom_args_items_per_row', [$this, 'wps_collections_custom_args_items_per_row']);
			add_filter('wps_collection_single_products_heading_class', [$this, 'wps_collection_single_products_heading_class']);
			add_filter('wps_products_pagination_range', [$this, 'wps_products_pagination_range']);
			add_filter('wps_products_pagination_next_link_text', [$this, 'wps_products_pagination_next_link_text']);
			add_filter('wps_products_pagination_prev_link_text', [$this, 'wps_products_pagination_prev_link_text']);
			add_filter('wps_products_pagination_first_page_text', [$this, 'wps_products_pagination_first_page_text']);
			add_filter('wps_products_pagination_show_as_prev_next', [$this, 'wps_products_pagination_show_as_prev_next']);
			add_filter('wps_products_pagination_prev_page_text', [$this, 'wps_products_pagination_prev_page_text']);
			add_filter('wps_products_pagination_next_page_text', [$this, 'wps_products_pagination_next_page_text']);
			add_filter('wps_products_args', [$this, 'wps_products_args']);
			add_filter('wps_products_args_posts_per_page', [$this, 'wps_products_args_posts_per_page']);
			add_filter('wps_products_args_orderby', [$this, 'wps_products_args_orderby']);
			add_filter('wps_products_args_paged', [$this, 'wps_products_args_paged']);
			add_filter('wps_products_custom_args', [$this, 'wps_products_custom_args']);
			add_filter('wps_products_custom_args_items_per_row', [$this, 'wps_products_custom_args_items_per_row']);
			add_filter('wps_products_price_multi', [$this, 'wps_products_price_multi'], 10, 4);
			add_filter('wps_products_price_one', [$this, 'wps_products_price_one'], 10, 2);
			add_action('wps_products_pagination', [$this, 'wps_products_pagination']);
			add_filter('wps_products_related_args_posts_per_page', [$this, 'wps_products_related_args_posts_per_page']);
			add_filter('wps_products_related_show', [$this, 'wps_products_related_show']);
			add_filter('wps_products_related_filters', [$this, 'wps_products_related_filters'], 10, 2);
			add_filter('wps_products_related_args_orderby', [$this, 'wps_products_related_args_orderby']);
			add_filter('wps_products_related_args', [$this, 'wps_products_related_args']);
			add_filter('wps_products_related_custom_args', [$this, 'wps_products_related_custom_args']);
			add_filter('wps_products_related_custom_items_per_row', [$this, 'wps_products_related_custom_items_per_row']);
			add_filter('wps_product_single_thumbs_class', [$this, 'wps_product_single_thumbs_class'], 10, 2);
			add_filter('wps_product_single_price', [$this, 'wps_product_single_price'], 10, 4);
			add_filter('wps_product_single_price_multi', [$this, 'wps_product_single_price_multi'], 10, 4);
			add_filter('wps_product_single_price_one', [$this, 'wps_product_single_price_one'], 10, 3);
			add_filter('wps_products_link', [$this, 'wps_products_link'], 10, 3);

		}


		/*

		Init

		*/
		public function init() {
			$this->hooks();
		}

	}

}
