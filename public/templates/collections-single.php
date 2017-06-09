<?php

use WPS\DB;
use WPS\DB\Products;

global $post;

$DB = new DB();
$DB_Products = new Products();

$collection = $DB->get_collection();
$products = $DB_Products->get_products_by_collection_id($collection[0]->collection_id);

// error_log(print_r($products, true));

get_header();

if (is_single()) {

  do_action('wps_collections_single_before');

  do_action('wps_collections_single_start', $collection);
  do_action('wps_collections_single_header', $collection);
  do_action('wps_collections_single_img', $collection);
  do_action('wps_collections_single_content', $collection);
  do_action('wps_collections_single_products', $collection, $products);
  do_action('wps_collections_single_end', $collection);

  do_action('wps_collections_single_after');

}

get_sidebar();
get_footer();
