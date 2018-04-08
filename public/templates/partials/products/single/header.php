<?php

/*

@description   Header for the product single page

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/single/header-price.php

@docs          https://wpshop.io/docs/templates/products/single/header-price

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<header class="wps-product-header">
  <?php do_action('wps_product_single_heading', $data->product); ?>
</header>
