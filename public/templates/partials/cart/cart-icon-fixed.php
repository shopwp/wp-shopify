<?php

/*

@description   Cart icon fixed. Used to contain both the cart counter and actual icon.

@version       1.0.0
@since         1.0.49
@path          templates/partials/cart/cart-icon-fixed.php

@docs          https://wpshop.io/docs/templates/cart/cart-icon-fixed

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<button
	class="<?= apply_filters( 'wps_cart_btn_class', ''); ?> wps-btn-cart-fixed wps-btn-cart wps-is-disabled wps-is-loading"
	style="background-color:<?=  $data->colors['background']; ?>">

  <?php

	if ($data->counter) {
		do_action('wps_cart_counter', $data->colors['counter']);
	}

  do_action('wps_cart_icon',  $data->colors['icon']);

  ?>

</button>
