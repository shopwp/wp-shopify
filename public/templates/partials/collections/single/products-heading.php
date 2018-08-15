<?php

/*

@description   Product list heading on single collection page

@version       1.0.1
@since         1.0.49
@path          templates/partials/collections/single/products-heading.php

@docs          https://wpshop.io/docs/templates/collections/single/products-heading

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<h2 class="wps-collections-products-heading <?= apply_filters('wps_collections_single_products_heading_class', ''); ?>">
  <?= apply_filters('wps_collections_single_products_heading', esc_html__('Products', WPS_PLUGIN_TEXT_DOMAIN)); ?>
</h2>
