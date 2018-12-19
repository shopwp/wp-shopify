<?php

/*

@description   Contains the actual add to cart button used for products. This is a global partial used by
               the shortcode, single page, and listing page.

@version       1.0.1
@since         1.0.49
@path          templates/partials/products/add-to-cart/button-add-to-cart.php

@docs          https://wpshop.io/docs/templates/products/add-to-cart/button-add-to-cart

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<div class="wps-btn-wrapper wps-col wps-col-<?= $data->button_width; ?> <?= apply_filters('wps_add_to_cart_wrapper_class', ''); ?>">

  <button
    itemprop="potentialAction"
    itemscope
    itemtype="https://schema.org/BuyAction"
    href="#!"
    class="wps-btn wps-col-1 wps-btn-secondary wps-add-to-cart <?= apply_filters('wps_add_to_cart_class', ''); ?>"
    title="<?php esc_attr_e($data->button_text, WPS_PLUGIN_TEXT_DOMAIN); ?>"
		style="<?= !empty($data->button_color) ? 'background-color: ' . $data->button_color . ';' : ''; ?>">

    <?php esc_html_e($data->button_text, WPS_PLUGIN_TEXT_DOMAIN); ?>

  </button>

</div>
