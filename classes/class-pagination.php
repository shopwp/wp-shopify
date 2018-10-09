<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;


class Pagination {

	public function __construct($DB_Settings_General, $Templates) {
		$this->DB_Settings_General 				= $DB_Settings_General;
		$this->Templates 									= $Templates;
	}


	/*

	Returns a string to be used within posts_clauses. E.g., "LIMIT 0, 10"

	*/
	public function construct_pagination_limits($query) {

		global $post;
		$wps_related_products = $query->get('wps_related_products');

		if (empty($wps_related_products)) {

			$currentPage = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

			if ($this->DB_Settings_General->get_num_posts() !== null) {

				$posts_per_page = $this->DB_Settings_General->get_num_posts();

				$minNumProducts = ($currentPage - 1) * $posts_per_page;
				$maxNumProducts = $posts_per_page;


			} else {

				$posts_per_page = get_option('posts_per_page');

				$minNumProducts = ($currentPage - 1) * $posts_per_page;
				$maxNumProducts = $posts_per_page;

			}

			$limit = 'LIMIT ' . $minNumProducts . ', ' . $maxNumProducts;

		} else {

			// Setting related products count
			$wps_related_products_count = $query->get('wps_related_products_count');
			$limit = 'LIMIT 0, ' . $wps_related_products_count;

		}

		return $limit;

	}


	/*

	Get Pagenum Link

	*/
	public function get_page_number_link($args, $page) {

		// Check for the post type
		if (!isset($args['query']->query['post_type'])) {
			$post_type = explode("_query", $args['query']->query['context'])[0];

		} else {
			$post_type = $args['query']->query['post_type'];
		}


		// Only run for WP Shopify custom post types
		if ($post_type !== WPS_PRODUCTS_POST_TYPE_SLUG && $post_type !== WPS_COLLECTIONS_POST_TYPE_SLUG) {
			return;
		}


		global $post;

		$general_settings = $this->DB_Settings_General->get();
		$homeURL = get_home_url();


		/*

		By default, use the permalink URLs set within plugin settings

		*/
		if ($post_type === WPS_PRODUCTS_POST_TYPE_SLUG) {
			$urlPrefix = $homeURL . '/' . $general_settings->url_products . '/';

		} else if ($post_type === WPS_COLLECTIONS_POST_TYPE_SLUG) {
			$urlPrefix = $homeURL . '/' . $general_settings->url_collections . '/';
		}


		/*

		If user wants to override the default permalinks for a cerain page
		while using the shortcode, they can pass in keep-permalinks="true"

		Will always override settings permalinks when passed in.

		*/
		if ( isset($args['query']->query['custom']['keep-permalinks']) ) {
			$urlPrefix = get_permalink($post->ID);
		}

		return esc_url($urlPrefix . 'page/' . $page);

	}


	/*

	Gets the max number of pages

	*/
	public function get_max_pages($args) {
		return ceil(($args['query']->found_posts / $this->DB_Settings_General->get_num_posts()));
	}


	/*

	Get current page if query is paginated and more than one page exists
	The first page is set to 1

	Static front pages is included

	@see WP_Query pagination parameter 'paged'
	@link http://codex.wordpress.org/Class_Reference/WP_Query#Pagination_Parameters

	*/
	public function get_current_page() {

		if ( get_query_var('paged') ) {
			$current_page = get_query_var('paged');

		} elseif ( get_query_var('page') ) {
			$current_page = get_query_var('page');

		} else{
			$current_page = 1;
		}

		return $current_page;

	}


	/*

	Gets the default pagination params

	*/
	public function get_pagination_defaults() {

		return [
			'query'                 => $GLOBALS['wp_query'],
			'previous_page_text'    => __( apply_filters('wps_products_pagination_prev_page_text', '&laquo;'), WPS_PLUGIN_TEXT_DOMAIN),
			'next_page_text'        => __( apply_filters('wps_products_pagination_next_page_text', '&raquo;'), WPS_PLUGIN_TEXT_DOMAIN),
			'first_page_text'       => __( apply_filters('wps_products_pagination_first_page_text', 'First'), WPS_PLUGIN_TEXT_DOMAIN),
			'last_page_text'        => __( apply_filters('wps_products_pagination_last_page_text', 'Last'), WPS_PLUGIN_TEXT_DOMAIN),
			'next_link_text'        => __( apply_filters('wps_products_pagination_next_link_text', 'Next'), WPS_PLUGIN_TEXT_DOMAIN),
			'previous_link_text'    => __( apply_filters('wps_products_pagination_prev_link_text', 'Prev'), WPS_PLUGIN_TEXT_DOMAIN),
			'show_posts_links'      => apply_filters('wps_products_pagination_show_as_prev_next', false),
			'range'                 => apply_filters('wps_products_pagination_range', 5),
		];

	}


	/*

	Get paginated numbers

	*/
	public function get_paginated_numbers($args = []) {


		// Exit if not enough posts to show pagination
		if ($args['query']->found_posts <= $this->DB_Settings_General->get_num_posts()) {
			return;
		}

		// Set defaults to use
		$defaults = $this->get_pagination_defaults();

		// Merge default arguments with user set arguments
		$args = wp_parse_args( $args, $defaults );


		// if ($amountOfProducts <= $settingsNumProducts) {}


		$current_page = $this->get_current_page();

		$max_pages = $this->get_max_pages($args);


		/*

		If $args['show_posts_links'] is set to false, numbered paginated links are returned
		If $args['show_posts_links'] is set to true, pagination links are returned

		*/
		if ($args['show_posts_links'] === false) {

			// Don't display links if only one page exists
			if ($max_pages === 1) {
				$paginated_text = '';

			} else {

				/*

				For multi-paged queries, we need to set the variable ranges which will be used to check
				the current page against and according to that set the correct output for the paginated numbers

				*/
				$mid_range      = (int) floor( $args['range'] / 2 );
				$start_range    = range( 1 , $mid_range );
				$end_range      = range( ( $max_pages - $mid_range +1 ) , $max_pages );
				$exclude        = array_merge( $start_range, $end_range );

				/*

				The amount of pages must now be checked against $args['range']. If the total amount of pages
				is less than $args['range'], the numbered links must be returned as is

				If the total amount of pages is more than $args['range'], then we need to calculate the offset
				to just return the amount of page numbers specified in $args['range']. This defaults to 5, so at any
				given instance, there will be 5 page numbers displayed

				*/
				$check_range = ( $args['range'] > $max_pages ) ? true : false;

				if ($check_range === true) {
					$range_numbers = range(1, $max_pages);

				} elseif ($check_range === false) {

					if (!in_array($current_page, $exclude)) {
						$range_numbers = range( ( $current_page - $mid_range ), ( $current_page + $mid_range ) );

					} elseif (in_array( $current_page, $start_range ) && ( $current_page - $mid_range ) <= 0 ) {
						$range_numbers = range(1, $args['range']);

					} elseif(in_array( $current_page, $end_range ) && ( $current_page + $mid_range ) >= $max_pages ) {

						$range_numbers = range( ( $max_pages - $args['range'] +1 ), $max_pages );

					}

				}


				/*

				The page numbers are set into an array through this foreach loop. The current page, or active page
				gets the class 'current' assigned to it. All the other pages get the class 'inactive' assigned to it

				*/
				foreach ($range_numbers as $page_number) {

					if ($page_number == $current_page) {

						ob_start();

						$this->Templates->Template_Loader->set_template_data([
							'page_number' => $page_number
						])->get_template_part( 'partials/pagination/page-number', 'current');

						$page_numbers[] = ob_get_contents();
						ob_end_clean();

					} else {

						$page_href = $this->get_page_number_link($args, $page_number);

						ob_start();

						$this->Templates->Template_Loader->set_template_data([
							'page_number' => $page_number,
							'page_href' 	=> $page_href
						])->get_template_part( 'partials/pagination/page', 'number');

						$page_numbers[] = ob_get_contents();
						ob_end_clean();

					}

				}

				/*

				All the texts are set here and when they should be displayed which will link back to:
				 - $previous_page The previous page from the current active page
				 - $next_page The next page from the current active page
				 - $first_page Links back to page number 1
				 - $last_page Links to the last page

				*/



				/*

				Previous page

				*/
				if ($current_page !== 1) {

					$page_href_previous = $this->get_page_number_link($args, $current_page - 1);

					ob_start();

					$this->Templates->Template_Loader->set_template_data([
						'page_previous_text'		=> $args['previous_page_text'],
						'page_number' 					=> $current_page - 1,
						'page_href' 						=> $page_href_previous
					])->get_template_part( 'partials/pagination/page', 'previous');

					$previous_page = ob_get_contents();
					ob_end_clean();


				} else {
					$previous_page = '';
				}


				/*

				Next page

				*/
				if ($current_page !== $max_pages) {

					$page_href_next = $this->get_page_number_link($args, $current_page + 1);

					ob_start();

					$this->Templates->Template_Loader->set_template_data([
						'page_next_text'		=> $args['next_page_text'],
						'page_number' 			=> $current_page - 1,
						'page_href' 				=> $page_href_next
					])->get_template_part( 'partials/pagination/page', 'next');

					$next_page = ob_get_contents();
					ob_end_clean();


				} else {
					$next_page = '';
				}


				/*

				First page

				*/
				if (!in_array( 1, $range_numbers)) {

					$page_href_first = $this->get_page_number_link($args, 1);

					ob_start();

					$this->Templates->Template_Loader->set_template_data([
						'page_first_text'		=> $args['first_page_text'],
						'page_number' 			=> $current_page - 1,
						'page_href' 				=> $page_href_first
					])->get_template_part( 'partials/pagination/page', 'first');

					$first_page = ob_get_contents();
					ob_end_clean();

				} else {
					$first_page = '';
				}


				/*

				Last page

				*/
				if (!in_array($max_pages, $range_numbers)) {

					$page_href_last = $this->get_page_number_link($args, $max_pages);

					ob_start();

					$this->Templates->Template_Loader->set_template_data([
						'page_last_text'		=> $args['last_page_text'],
						'page_number' 			=> $current_page - 1,
						'page_href' 				=> $page_href_last
					])->get_template_part( 'partials/pagination/page', 'last');

					$last_page = ob_get_contents();
					ob_end_clean();


				} else {
					$last_page = '';
				}


				// Removes next link on last page of pagination
				if ( $max_pages == $current_page) {
					$next_page = '';
				}


				ob_start();
				$this->Templates->Template_Loader->set_template_data([
					'page_number' 			=> $current_page,
					'max_pages' 				=> $max_pages
				])->get_template_part( 'partials/pagination/counter' );

				$page_text = ob_get_contents();
				ob_end_clean();


				// Turn the array of page numbers into a string
				$numbers_string = implode( ' ', $page_numbers );

				$paginated_text = apply_filters('wps_products_pagination_start', '<div class="wps-products-pagination">');
				$paginated_text .= $page_text . $first_page . $previous_page . $numbers_string . $next_page . $last_page;
				$paginated_text .= apply_filters('wps_products_pagination_end', '</div>');

			}

		} elseif ($args['show_posts_links'] === true) {

			/*

			If $args['show_posts_links'] is set to true, only links to the previous and next pages are displayed
			The $max_pages parameter is already set by the function to accommodate custom queries

			*/
			$paginated_text = apply_filters('wps_products_pagination_start', '<div itemscope itemtype="https://schema.org/SiteNavigationElement" class="wps-products-pagination">');
			$paginated_text .= previous_posts_link( '<div class="wps-pagination-products-prev-link">' . __($args['previous_link_text']) . '</div>' );
			$paginated_text .= next_posts_link( '<div class="wps-pagination-products-next-link">' . __($args['next_link_text']) . '</div>', $max_pages );
			$paginated_text .= apply_filters('wps_products_pagination_end', '</div>');

		}

		// Finally return the output text from the function
		return $paginated_text;

	}

}
