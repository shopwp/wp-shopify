<?php

/*

@description   Cart icon counter. The number shown inside the cart icon.

@version       1.0.0
@since         1.0.49
@path          templates/partials/cart/cart-counter.php

@docs          https://wpshop.io/docs/templates/cart/cart-counter

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<span class="<?= apply_filters( 'wps_cart_counter_class', ''); ?> wps-cart-counter wps-is-hidden"></span>
