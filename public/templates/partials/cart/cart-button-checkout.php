<?php

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<a href="<?php echo esc_url('https://checkout.shopify.com'); ?>" class="wps-btn wps-btn-checkout" target="_self" title="Checkout" id="wps-btn-checkout">
  <?php echo apply_filters( 'wps_cart_checkout_text', esc_html__('Checkout', 'wp-shopify')); ?>
</a>
