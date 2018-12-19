<?php

/*

@description   Description for each product within the main products loop

@version       1.0.0
@since         1.3.1
@path          templates/partials/products/loop/item-description.php

@docs          https://wpshop.io/docs/templates/partials/products/loop/item-description

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<div
  itemprop="description"
	class="wps-products-description <?= apply_filters( 'wps_products_description_class', '' ); ?>">
  <?php _e($data->product->body_html, WPS_PLUGIN_TEXT_DOMAIN); ?>
</div>
