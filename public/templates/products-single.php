<?php

/*

@description   The main entry point for the 'products single' page. Used internally by the custom post type single template

@version       1.0.0
@since         1.0.49
@path          templates/products-single.php
@partials      templates/partials/products/single

@docs          https://wpshop.io/docs/templates/products-single

*/

use WPS\DB\Products;
use WPS\Utils;

if ( !defined('ABSPATH') ) {
	exit;
}

$DB_Products = new Products();
$product = $DB_Products->get_data();

get_header('wps');

if ( is_single() ) {


	do_action('wps_breadcrumbs');
	
  do_action('wps_product_single_start', $product);

  do_action('wps_product_single_before', $product);
  do_action('wps_product_single_gallery_start', $product);

  do_action('wps_product_imgs_before', $product);
  do_action('wps_product_single_imgs', $product);
  do_action('wps_product_imgs_after', $product);

  do_action('wps_product_single_gallery_end', $product);
  do_action('wps_product_single_info_start', $product);

  do_action('wps_product_single_header_before', $product);
  do_action('wps_product_single_header', $product);
  do_action('wps_product_single_header_after', $product);

  do_action('wps_products_price_before', $product);
  do_action('wps_products_price', $product);
  do_action('wps_products_price_after', $product);

  do_action('wps_product_single_content_before', $product);
  do_action('wps_product_single_content', $product);
  do_action('wps_product_single_content_after', $product);

  do_action('wps_products_meta_start', $product);
  do_action('wps_products_quantity', $product);
  do_action('wps_product_single_actions_group_start', $product);

  if(count($product->variants) > 1) {
    do_action('wps_products_options', $product);
  }

  if (Utils::product_inventory($product)) {
    do_action('wps_products_button_add_to_cart', $product);

  } else {
    do_action('wps_products_notice_out_of_stock', $product);
  }

  do_action('wps_product_cart_buttons_after', $product);
  do_action('wps_product_single_actions_group_end', $product);
  do_action('wps_products_notice_inline', $product);
  do_action('wps_products_meta_end', $product);
  do_action('wps_product_single_info_end', $product);
  do_action('wps_product_single_end', $product);

  do_action('wps_product_single_after', $product);

}

do_action('wps_product_single_sidebar');

get_footer('wps');
