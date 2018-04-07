<?php

/*

@description   Opening tag used to group the add to cart and variant selection

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/action-group/action-groups-start.php

@docs          https://wpshop.io/docs/templates/products/action-groups/action-groups-start

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<div class="wps-row wps-row-justify l-col-center wps-product-actions-group <?php echo apply_filters('wps_products_actions_class', ''); ?>">
