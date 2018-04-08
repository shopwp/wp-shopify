<?php

/*

@description   The main entry point for the related products component. Shows by default.

@version       1.0.0
@since         1.0.49
@path          templates/products-related.php
@partials      templates/partials/products/related

@docs          https://wpshop.io/docs/templates/products-related

*/

if ( !defined('ABSPATH') ) {
	exit;
}

do_action('wps_products_related_before');
do_action('wps_products_related_start');

do_action('wps_products_related_heading_before');
do_action('wps_products_related_heading');
do_action('wps_products_related_heading_after');

/*

Here we need to adjust the arguments array based on what the user wants to do. By default,
we'll select 4 random products to display (excluding the product currently shown). However
the developer may override this by passing in their own config.

*/
do_action(
  'wps_products_display',
  apply_filters('wps_products_related_args', []),
  apply_filters('wps_products_related_custom_args', [])
);

do_action('wps_products_related_end');
do_action('wps_products_related_after');
