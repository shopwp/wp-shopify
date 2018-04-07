<?php

/*

@description   Used to display the majority of content for the 'products all' page.

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/all-display.php
@partials      templates/partials/products

@docs          https://wpshop.io/docs/templates/products/all-display

*/

if ( !defined('ABSPATH') ) {
	exit;
}


do_action( 'wps_products_header', $data->query );
do_action( 'wps_products_header_after', $data->query );

do_action( 'wps_products_before', $data->query );

if ($data->amount_of_products > 0) {

  do_action( 'wps_products_loop_start', $data->query );

  foreach ($data->products as $product) {

    do_action( 'wps_products_item_start', $product, $data->args, $data->custom_args );
    do_action( 'wps_products_item', $product, $data->args, $data->settings );
    do_action( 'wps_products_item_end', $product );

  }

  wp_reset_postdata();

  do_action( 'wps_products_loop_end', $data->query ); // partials/products/loop/loop-end
  do_action( 'wps_before_products_pagination', $data->query );

  if (isset($data->args->paged) && $data->args->paged) {
    do_action( 'wps_products_pagination', $data->query );
  }

  do_action( 'wps_after_products_pagination', $data->query );

} else {

  do_action( 'wps_products_no_results', $data->args );

}

do_action( 'wps_products_after', $data->query );
