<?php

/*

@description   Cart checkout button

@version       1.0.0
@since         1.0.49
@path          templates/partials/cart/cart-button-checkout.php

@docs          https://wpshop.io/docs/templates/cart/cart-button-checkout

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<a href="<?= esc_url($data->checkout_base_url); ?>" class="wps-btn wps-btn-checkout" target="_self" title="Checkout" id="wps-btn-checkout">
  <?= apply_filters( 'wps_cart_checkout_text', esc_html__('Checkout', 'wp-shopify')); ?>
</a>
