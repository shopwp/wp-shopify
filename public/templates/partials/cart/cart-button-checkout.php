<?php

/*

@description   Cart checkout button

@version       1.0.1
@since         1.0.49
@path          templates/partials/cart/cart-button-checkout.php

@docs          https://wpshop.io/docs/templates/cart/cart-button-checkout

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<a
	href="<?= esc_url($data->checkout_base_url); ?>"
	class="wps-btn wps-btn-checkout <?= apply_filters( 'wps_cart_checkout_button_class', ''); ?>"
	target="_self"
	title="Checkout"
	style="<?= !empty($data->button_color) ? 'background-color: ' . $data->button_color . ';' : ''; ?>"
	id="wps-btn-checkout">
  	<?= apply_filters( 'wps_cart_checkout_text', esc_html__('Checkout', WPS_PLUGIN_TEXT_DOMAIN)); ?>
</a>
