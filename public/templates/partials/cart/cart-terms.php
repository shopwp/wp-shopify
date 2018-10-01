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

<div class="wps-cart-terms">

	<input type="checkbox" name="" value="0" id="wps-terms-checkbox">
	<label for="wps-terms-checkbox" class="wps-terms-label"><?= $data->terms_content; ?></label>

</div>
