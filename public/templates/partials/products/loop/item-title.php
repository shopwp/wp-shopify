<?php

/*

@description   Title for each product within the main products loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/loop/item-title.php

@docs          https://wpshop.io/docs/templates/partials/products/loop/item-title

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<h2
  itemprop="name"
  class="wps-products-title <?= apply_filters( 'wps_products_title_class', '' ); ?>">
  <?php esc_html_e($data->product->title, WPS_PLUGIN_TEXT_DOMAIN); ?>
</h2>
