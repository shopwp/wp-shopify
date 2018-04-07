<?php

/*

@description   The main entry point for the 'collections single' page. Used internally by the custom post type single template

@version       1.0.0
@since         1.0.49
@path          templates/collections-single.php
@partials      templates/partials/collections/single

@docs          https://wpshop.io/docs/templates/collections-single

*/


/*

Need to perform business logic here because the WordPress filter add_filter('single_template')
doesn't allow for variables to be passed through

*/
use WPS\DB;
use WPS\Utils;
use WPS\DB\Products;

if ( !defined('ABSPATH') ) {
	exit;
}

global $post;

$DB = new DB();
$DB_Products = new Products();
$collection = $DB->get_collection($post->ID);

if ( is_object($collection[0]) && property_exists($collection[0], 'collection_id')) {
  $products = $DB_Products->get_products_by_collection_id($collection[0]->collection_id);

} else {
  $products = [];
}


get_header('wps');

do_action('wps_breadcrumbs');
do_action('wps_collection_single_before');
do_action('wps_collection_single_start', $collection);
do_action('wps_collection_single_header', $collection);
do_action('wps_collection_single_content', $collection);
do_action('wps_collection_single_products', $collection, $products);
do_action('wps_collection_single_end', $collection);
do_action('wps_collection_single_after');
do_action('wps_collection_single_sidebar');

get_footer('wps');
