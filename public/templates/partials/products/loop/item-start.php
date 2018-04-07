<?php

/*

@description   Opening tags for each product item within the main products loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/loop/item-start.php

@docs          https://wpshop.io/docs/templates/partials/products/loop/item-start

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<li class="wps-product-item wps-product-item-id-<?php echo $data->product->id; ?> wps-col wps-col-<?php echo apply_filters( 'wps_products_per_row', $data->items_per_row ); ?> <?php echo apply_filters('wps_product_class', ''); ?>">

  <div itemscope itemtype="https://schema.org/Product" class="wps-box">
