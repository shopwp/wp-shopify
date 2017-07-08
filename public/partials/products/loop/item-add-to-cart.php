<?php

do_action('wps_products_meta_start', $productWithVariants);
do_action('wps_products_quantity', $productWithVariants);
do_action('wps_products_actions_group_start', $productWithVariants);

if(count($productWithVariants['variants']) > 1) {
  do_action('wps_products_options', $productWithVariants);
}

do_action('wps_products_button_add_to_cart', $productWithVariants);
do_action('wps_products_cart_buttons_after', $productWithVariants);
do_action('wps_products_actions_group_end', $productWithVariants);
do_action('wps_products_notice_inline', $productWithVariants);
do_action('wps_products_meta_end', $productWithVariants);
