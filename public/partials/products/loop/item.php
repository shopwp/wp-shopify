<?php

use WPS\DB\Products;
use WPS\Utils;

do_action('wps_products_item_before', $product);
do_action('wps_products_img_before', $product);

do_action('wps_products_item_link_start', $product, $settings);
do_action('wps_products_img', $product);
do_action('wps_products_title_before', $product);
do_action('wps_products_title', $product);
do_action('wps_products_item_link_end', $product);

do_action('wps_products_price_before', $product);
do_action('wps_products_price', $product);
do_action('wps_products_price_after', $product);

if (is_single()) {

  if (apply_filters('wps_products_related_show_add_to_cart', false)) {

    if (get_transient('wps_product_with_variants_' . $product->product_id)) {
      $productWithVariants = get_transient('wps_product_with_variants_' . $product->product_id);

    } else {

      $DB_Products = new Products();
      $productWithVariants = $DB_Products->get_data($product->post_id);
      set_transient('wps_product_with_variants_' . $product->product_id, $productWithVariants);

    }

    do_action('wps_products_add_to_cart', $productWithVariants);

  }

} else {

  if (apply_filters('wps_products_show_add_to_cart', false)) {

    if (get_transient('wps_product_with_variants_' . $product->product_id)) {
      $productWithVariants = get_transient('wps_product_with_variants_' . $product->product_id);

    } else {

      $DB_Products = new Products();
      $productWithVariants = $DB_Products->get_data($product->post_id);
      set_transient('wps_product_with_variants_' . $product->product_id, $productWithVariants);

    }

    // Only shows if total product inventory > 0
    if (Utils::product_inventory($productWithVariants)) {
      do_action('wps_products_add_to_cart', $productWithVariants);

    } else {
      do_action('wps_products_notice_out_of_stock', $productWithVariants);
    }

  }

}

do_action('wps_products_item_after', $product);
