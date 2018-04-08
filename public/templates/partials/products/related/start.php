<?php

/*

@description   Opening tag for related products

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/related/start.php

@docs          https://wpshop.io/docs/templates/products/related/start

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<section class="wps-related-products wps-contain <?= apply_filters('wps_related_products_class', ''); ?>">
