<?php

namespace WPS\Layout;

use WPS\Utils;
use WPS\Utils\Data as Utils_Data;

if (!defined('ABSPATH')) {
	exit;
}


class Data {


	/*

	Initialize the class and set its properties.

	*/
	public function __construct() {

	}


  /*

	Map products shortcode arguments

	Defines the available shortcode arguments by checking
	if they exist and applying them to the custom property.

	The returned value eventually gets passed to wps_clauses_mod

	*/
	public function build_query_params($shortcode_args) {

		$data = [
			'post_type'         => WPS_PRODUCTS_POST_TYPE_SLUG,
			'post_status'       => 'publish',
			'paged'             => 1
		];

		//
		// Order
		//
		if ( !empty($shortcode_args['order']) ) {
			$data['custom']['order'] = strtolower( $shortcode_args['order'] );
		}

		//
		// Order by
		//
		if ( !empty($shortcode_args['orderby']) ) {
			$data['custom']['orderby'] = strtolower( $shortcode_args['orderby'] );
		}

		//
		// IDs
		//
		if ( !empty($shortcode_args['ids']) ) {
			$data['custom']['ids'] = $shortcode_args['ids'];
		}

		//
		// Meta Slugs
		//
		if ( !empty($shortcode_args['slugs']) ) {
			$data['custom']['slugs'] = $shortcode_args['slugs'];
		}

		//
		// Meta Title
		//
		if ( !empty($shortcode_args['titles']) ) {
			$data['custom']['titles'] = $shortcode_args['titles'];
		}

		//
		// Descriptions
		//
		if ( !empty($shortcode_args['desc']) ) {
			$data['custom']['desc'] = $shortcode_args['desc'];
		}

		//
		// Tags
		//
		if ( !empty($shortcode_args['tags']) ) {
			$data['custom']['tags'] = $shortcode_args['tags'];
		}

		//
		// Vendors
		//
		if ( !empty($shortcode_args['vendors']) ) {
			$data['custom']['vendors'] = $shortcode_args['vendors'];
		}

		//
		// Variants
		//
		if ( !empty($shortcode_args['variants']) ) {
			$data['custom']['variants'] = $shortcode_args['variants'];
		}

		//
		// Type
		//
		if ( !empty($shortcode_args['types']) ) {
			$data['custom']['types'] = $shortcode_args['types'];
		}

		//
		// Options
		//
		if ( !empty($shortcode_args['options']) ) {
			$data['custom']['options'] = $shortcode_args['options'];
		}

		//
		// Available
		//
		if ( !empty($shortcode_args['available']) ) {
			$data['custom']['available'] = $shortcode_args['available'];
		}

		//
		// Collections
		//
		if ( !empty($shortcode_args['collections']) ) {
			$data['custom']['collections'] = $shortcode_args['collections'];
		}

		//
		// Collection Slugs
		//
		if ( !empty($shortcode_args['collection_slugs']) ) {
			$data['custom']['collection_slugs'] = $shortcode_args['collection_slugs'];
		}

		//
		// Limit
		//
		if ( !empty($shortcode_args['limit']) ) {
			$data['custom']['limit'] = $shortcode_args['limit'];
		}

		//
		// Items per row
		//
		if ( !empty($shortcode_args['items-per-row']) ) {
			$data['custom']['items-per-row'] = $shortcode_args['items-per-row'];
		}

		//
		// Pagination
		//
		if ( !empty($shortcode_args['pagination']) ) {
			$data['custom']['pagination'] = false;
		}

		//
		// Page
		//
    // TODO: I dont think this is used anymore, revist
    //
		if ( !empty($shortcode_args['page']) ) {
			$data['paged'] = $shortcode_args['page'];
		}

		//
		// Add to cart
		//
		if ( !empty($shortcode_args['add-to-cart']) ) {
			$data['custom']['add-to-cart'] = $shortcode_args['add-to-cart'];
		}


		//
		// Add to cart text
		//
		if ( !empty($shortcode_args['add-to-cart-text']) ) {
			$data['custom']['add-to-cart-text'] = $shortcode_args['add-to-cart-text'];
		}


		//
		// Breadcrumbs
		//
		if ( !empty($shortcode_args['breadcrumbs']) ) {
			$data['custom']['breadcrumbs'] = $shortcode_args['breadcrumbs'];
		}

		//
		// Keep permalinks
		//
		if ( !empty($shortcode_args['keep-permalinks']) ) {
			$data['custom']['keep-permalinks'] = $shortcode_args['keep-permalinks'];
		}

		//
		// Keep permalinks
		//
		if ( !empty($shortcode_args['description']) ) {
			$data['custom']['description'] = $shortcode_args['description'];
		}

		return $data;

	}




  /*

  Formats shortcode attribute

  UNDER TEST

  */
  public function format_shortcode_attr($arg_value) {

    if ( !is_string($arg_value) ) {
      $arg_value = '';
    }

    if ( Utils_Data::contains_comma($arg_value) ) {

      return array_filter( Utils::comma_list_to_array( trim($arg_value) ) );

    } else {

      return trim($arg_value);

    }

  }


  /*

  Formats shortcode attributeS

  UNDER TEST

  */
  public function format_shortcode_attrs($shortcode_args) {

    foreach ($shortcode_args as $arg_name => $arg_value) {
      $shortcode_args[$arg_name] = $this->format_shortcode_attr($arg_value);
    }

    return $shortcode_args;

  }


  public function standardize_layout_data($shortcode_args) {

    if ( !isset($shortcode_args) || !$shortcode_args) {
      return [];
    }

    return $this->format_shortcode_attrs($shortcode_args);

  }


  /*

  Formats products shortcode args
  Returns SQL query

  TODO: Combine with wps_format_collections_shortcode_args

  */
  public function format_products_shortcode_args($shortcode_args) {
    return $this->build_query_params( $this->standardize_layout_data($shortcode_args) );
  }


}
