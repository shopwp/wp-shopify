<?php

use WPS\DB\Products;
use WPS\Utils;

// global $post;

$DB_Products = new Products();
$wps_product = $DB_Products->get_data();

get_header('wps');

if ( is_single() ) {

  do_action('wps_product_single_before', $wps_product);

  do_action('wps_product_single_start', $wps_product);
  do_action('wps_product_single_gallery_start', $wps_product);
  do_action('wps_product_imgs_before', $wps_product);
  do_action('wps_product_single_imgs', $wps_product);
  do_action('wps_product_imgs_after', $wps_product);
  do_action('wps_product_single_gallery_end', $wps_product);
  do_action('wps_product_single_info_start', $wps_product);
  do_action('wps_product_single_header_before', $wps_product);
  do_action('wps_product_single_header', $wps_product);
  do_action('wps_product_single_header_after', $wps_product);
  do_action('wps_product_single_content', $wps_product);
  do_action('wps_product_single_meta_start', $wps_product);
  do_action('wps_product_single_quantity', $wps_product);
  do_action('wps_product_single_actions_group_start', $wps_product);

  if(count($wps_product['variants']) > 1) {
    do_action('wps_product_single_options', $wps_product);
  }

  if ( !empty(Utils::product_inventory($wps_product)) ) {
    do_action('wps_product_single_button_add_to_cart', $wps_product);

  } else {
    do_action('wps_products_notice_out_of_stock', $wps_product);
  }

  do_action('wps_product_cart_buttons_after', $wps_product);
  do_action('wps_product_single_actions_group_end', $wps_product);
  do_action('wps_product_single_notice_inline', $wps_product);
  do_action('wps_product_single_meta_end', $wps_product);
  do_action('wps_product_single_info_end', $wps_product);
  do_action('wps_product_single_end', $wps_product);

  do_action('wps_product_single_after', $wps_product);

}

do_action('wps_product_single_sidebar');

get_footer('wps');
