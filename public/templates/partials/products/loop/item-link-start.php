<?php

/*

@description   Opening link tag for each product within the main products loop

@version       1.0.1
@since         1.0.49
@path          templates/partials/products/loop/item-link-start.php

@docs          https://wpshop.io/docs/templates/partials/products/loop/item-link-start

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<a href="<?= apply_filters( 'wps_products_link', esc_url( home_url() . '/' . $data->settings->url_products . '/' . $data->product->handle ), $data->product ); ?>" class="wps-product-link <?= apply_filters( 'wps_products_link_class', '' ); ?>" title="<?php esc_attr_e($data->product->title, WPS_PLUGIN_TEXT_DOMAIN); ?>">
