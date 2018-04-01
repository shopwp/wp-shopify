<?php

do_action('wps_products_meta_start', $data->product);
do_action('wps_products_quantity', $data->product);
do_action('wps_products_actions_group_start', $data->product);

if (count($data->product->variants) > 1) {
  do_action('wps_products_options', $data->product);
}

do_action('wps_products_button_add_to_cart', $data->product);
do_action('wps_products_cart_buttons_after', $data->product);
do_action('wps_products_actions_group_end', $data->product);
do_action('wps_products_notice_inline', $data->product);
do_action('wps_products_meta_end', $data->product);
