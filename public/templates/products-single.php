<?php

use WPS\DB\Products;

global $post;

$DB_Products = new Products();
$product = $DB_Products->get_data();

get_header();

if (is_single()) {

  do_action('wps_product_single_before', $product);

  while (have_posts()) : the_post();

    do_action('wps_product_single_start', $product);
    do_action('wps_product_single_gallery_start', $product);
    do_action('wps_product_imgs_before', $product);
    do_action('wps_product_single_imgs', $product);
    do_action('wps_product_imgs_after', $product);
    do_action('wps_product_single_gallery_end', $product);
    do_action('wps_product_single_info_start', $product);
    do_action('wps_product_single_header', $product);
    do_action('wps_product_single_content', $product);
    do_action('wps_product_single_meta_start', $product);
    do_action('wps_product_single_quantity', $product);
    do_action('wps_product_single_actions_group_start', $product);

    if(count($product['variants']) > 1) {
      do_action('wps_product_single_options', $product);
    }

    do_action('wps_product_single_button_add_to_cart', $product);
    do_action('wps_product_cart_buttons_after', $product);
    do_action('wps_product_single_actions_group_end', $product);
    do_action('wps_product_single_notice_inline', $product);
    do_action('wps_product_single_meta_end', $product);
    do_action('wps_product_single_info_end', $product);
    do_action('wps_product_single_end', $product);

  endwhile;

  wp_reset_postdata();
  do_action('wps_product_single_after', $product);

}

get_sidebar();
get_footer();
