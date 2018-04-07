<?php

/*

@description   This partial contains the quantity, dropdowns, and add to cart button within the products loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/loop/item-add-to-cart.php

@docs          https://wpshop.io/docs/templates/partials/products/loop/item-add-to-cart

*/

if ( !defined('ABSPATH') ) {
	exit;
}

do_action('wps_products_meta_start', $data->product);
do_action('wps_products_quantity', $data->product);
do_action('wps_products_actions_group_start', $data->product);
do_action('wps_products_options', $data->product);
do_action('wps_products_button_add_to_cart', $data->product);
do_action('wps_products_cart_buttons_after', $data->product);
do_action('wps_products_actions_group_end', $data->product);
do_action('wps_products_notice_inline', $data->product);
do_action('wps_products_meta_end', $data->product);
