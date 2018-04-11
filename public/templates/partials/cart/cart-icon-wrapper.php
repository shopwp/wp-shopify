<?php

/*

@description   Cart icon wrapper. Used to contain both the cart counter and actual icon.

@version       1.0.0
@since         1.0.49
@path          templates/partials/cart/cart-icon-wrapper.php

@docs          https://wpshop.io/docs/templates/cart/cart-icon-wrapper

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<button class="<?= apply_filters( 'wps_cart_btn_class', ''); ?> wps-btn-cart wps-is-disabled wps-is-loading">

  <?php

	if ($data->counter) {
		do_action('wps_cart_counter');
	}

  do_action('wps_cart_icon');

  ?>

</button>
