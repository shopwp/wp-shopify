<?php

/*

@description   Opening link tag for each product within the main products loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/loop/item-link-start.php

@docs          https://wpshop.io/docs/templates/partials/products/loop/item-link-start

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<a href="<?php echo esc_url( home_url() . '/' . $data->settings->url_products . '/' . $data->product->handle ); ?>" class="wps-product-link <?php echo apply_filters( 'wps_products_link_class', '' ); ?>" title="<?php esc_attr_e($data->product->title, 'wp-shopify'); ?>">
