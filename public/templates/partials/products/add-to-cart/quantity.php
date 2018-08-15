<?php

/*

@description   Quantity component

@version       1.0.1
@since         1.0.49
@path          templates/partials/products/add-to-cart/quantity.php

@docs          https://wpshop.io/docs/templates/partials/products/add-to-cart/quantity

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<div class="wps-form-control wps-row wps-row-center wps-col-center wps-product-quantity-wrapper <?= apply_filters('wps_products_quantity_class', ''); ?>">
  <label for="wps-product-quantity">
    <?= apply_filters('wps_products_quantity_label', esc_html__('Quantity', WPS_PLUGIN_TEXT_DOMAIN)); ?>
  </label>
  <input type="number" name="wps-product-quantity" class="wps-product-quantity wps-form-input" value="1" min="0">
</div>
