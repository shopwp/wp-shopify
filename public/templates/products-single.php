<?php

/*

@description   The main entry point for the 'products single' page. Used internally by the custom post type single template

@version       1.0.2
@since         1.0.49
@path          templates/products-single.php
@partials      templates/partials/products/single

@docs          https://wpshop.io/docs/templates/products-single

*/

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\Utils;
use WPS\Factories\Templates_Factory;

$Templates = Templates_Factory::build();
$wps_product = $Templates->get_product_data();

get_header('wps');

if ( is_single() ) {

	do_action('wps_breadcrumbs');
  do_action('wps_product_single_start', $wps_product);

  do_action('wps_product_single_before', $wps_product);
  do_action('wps_product_single_gallery_start', $wps_product);

  do_action('wps_product_imgs_before', $wps_product);
  do_action('wps_product_single_imgs', $wps_product);
  do_action('wps_product_imgs_after', $wps_product);

  do_action('wps_product_single_gallery_end', $wps_product);
  do_action('wps_product_single_info_start', $wps_product);

  do_action('wps_product_single_header_before', $wps_product);
  do_action('wps_product_single_header', $wps_product);
  do_action('wps_product_single_header_after', $wps_product);

  do_action('wps_products_price_before', $wps_product);
  do_action('wps_products_price', $wps_product);
  do_action('wps_products_price_after', $wps_product);

  do_action('wps_product_single_content_before', $wps_product);
  do_action('wps_product_single_content', $wps_product);
  do_action('wps_product_single_content_after', $wps_product);

  do_action('wps_products_meta_start', $wps_product);
  do_action('wps_products_quantity', $wps_product);
  do_action('wps_product_single_actions_group_start', $wps_product);

  if (count($wps_product->variants) > 1) {
    do_action('wps_products_options', $wps_product);
  }

  if (Utils::product_inventory($wps_product)) {
    do_action('wps_products_button_add_to_cart', $wps_product);

  } else {
    do_action('wps_products_notice_out_of_stock', $wps_product);
  }

  do_action('wps_product_cart_buttons_after', $wps_product);
  do_action('wps_product_single_actions_group_end', $wps_product);
  do_action('wps_products_notice_inline', $wps_product);
  do_action('wps_products_meta_end', $wps_product);
  do_action('wps_product_single_info_end', $wps_product);
  do_action('wps_product_single_end', $wps_product);

  do_action('wps_product_single_after', $wps_product);

}

do_action('wps_product_single_sidebar');

get_footer('wps');
