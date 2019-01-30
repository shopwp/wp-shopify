<?php

/*

@description   Runs for each item (product) within the main products loop

@version       1.1.0
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


if (isset($data->args->custom->description) && $data->args->custom->description === 'true') {
	do_action('wps_products_description', $data->product);
}

do_action('wps_products_price_before', $data->product);

if ($data->settings->products_compare_at) {
	do_action('wps_products_price_wrapper_start', $data->product);
	do_action('wps_products_compare_at_price', $data->product, true);
}

do_action('wps_products_price', $data->product);

if ($data->settings->products_compare_at) {
	do_action('wps_products_price_wrapper_end', $data->product);
}



do_action('wps_products_price_after', $data->product);


// If is_singular, then we're on the related products section
if ( is_singular(WPS_PRODUCTS_POST_TYPE_SLUG) ) {

  if ( apply_filters('wps_products_related_show_add_to_cart', false) ) {

    if (get_transient('wps_product_with_variants_' . $data->product->product_id)) {
      $product_with_variants = get_transient('wps_product_with_variants_' . $data->product->product_id);

    } else {

      $product_with_variants = $data->product_details;
      set_transient('wps_product_with_variants_' . $data->product->product_id, $product_with_variants);

    }

    do_action('wps_products_add_to_cart', $product_with_variants);

  }


} else {

  if ( apply_filters('wps_products_show_add_to_cart', false) ) {

    if ( get_transient('wps_product_with_variants_' . $data->product->product_id) ) {
      $product_with_variants = get_transient('wps_product_with_variants_' . $data->product->product_id);

    } else {

      $product_with_variants = $data->product_details;

      set_transient('wps_product_with_variants_' . $data->product->product_id, $product_with_variants);

    }

    // Only shows if total product inventory > 0
    if ( Utils::has_available_variants($product_with_variants->variants) ) {
      do_action('wps_products_add_to_cart', $product_with_variants);

    } else {
      do_action('wps_products_notice_out_of_stock', $product_with_variants);
    }

  }

}

do_action('wps_products_item_after', $data->product);
