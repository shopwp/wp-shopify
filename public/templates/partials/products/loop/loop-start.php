<?php

/*

@description   Opening tag for the main products loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/loop/loop-start.php

@docs          https://wpshop.io/docs/templates/partials/products/loop/loop-start

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<ul class="wps-row wps-contain wps-products <?php echo apply_filters('wps_products_class', ''); ?>">
