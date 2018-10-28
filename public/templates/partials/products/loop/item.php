<?php

/*

@description   Runs for each item (product) within the main products loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/loop/item.php

@docs          https://wpshop.io/docs/templates/partials/products/loop/item

*/

use WPS\Utils;

if ( !defined('ABSPATH') ) {
	exit;
}

do_action('wps_products_item_before', $data->product);
do_action('wps_products_img_before', $data->product);
do_action('wps_products_item_link_start', $data->product, $data->settings);
do_action('wps_products_img', $data->product);

do_action('wps_products_title_before', $data->product);
do_action('wps_products_title', $data->product);
do_action('wps_products_item_link_end', $data->product);

do_action('wps_products_price_before', $data->product);
do_action('wps_products_price', $data->product);
do_action('wps_products_price_after', $data->product);


// If single, then we're on the related products section
if ( is_single() ) {

  if ( apply_filters('wps_products_related_show_add_to_cart', false) ) {

    if (get_transient('wps_product_with_variants_' . $data->product->product_id)) {
      $productWithVariants = get_transient('wps_product_with_variants_' . $data->product->product_id);

    } else {

      $productWithVariants = $data->product_details;
      set_transient('wps_product_with_variants_' . $data->product->product_id, $productWithVariants);

    }

    do_action('wps_products_add_to_cart', $productWithVariants);

  }


} else {

  if ( apply_filters('wps_products_show_add_to_cart', false) ) {

    if ( get_transient('wps_product_with_variants_' . $data->product->product_id) ) {
      $productWithVariants = get_transient('wps_product_with_variants_' . $data->product->product_id);

    } else {

      $productWithVariants = $data->product_details;

      set_transient('wps_product_with_variants_' . $data->product->product_id, $productWithVariants);

    }

    // Only shows if total product inventory > 0
    if ( Utils::product_inventory($productWithVariants) ) {
      do_action('wps_products_add_to_cart', $productWithVariants);

    } else {
      do_action('wps_products_notice_out_of_stock', $productWithVariants);
    }

  }

}

do_action('wps_products_item_after', $data->product);
