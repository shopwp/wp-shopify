<?php

use WPS\DB;
use WPS\DB\Products;

global $post;

$WPS_DB = new DB();
$WPS_DB_Products = new Products();

$wps_collection = $WPS_DB->get_collection();
$wps_products = $WPS_DB_Products->get_products_by_collection_id($wps_collection[0]->collection_id);

get_header('wps');

if (is_single()) {

  do_action('wps_collection_single_before');

  do_action('wps_collection_single_start', $wps_collection);
  do_action('wps_collection_single_header', $wps_collection);

  do_action('wps_collection_single_content', $wps_collection);
  do_action('wps_collection_single_products', $wps_collection, $wps_products);
  do_action('wps_collection_single_end', $wps_collection);

  do_action('wps_collection_single_after');

}

do_action('wps_collection_single_sidebar');

get_footer('wps');
