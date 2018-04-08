<?php

/*

@description   Product header within loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/loop/header.php

@docs          https://wpshop.io/docs/templates/partials/products/loop/header

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<header class="wps-products-header wps-contain wps-row <?= apply_filters('wps_products_header_class', ''); ?>">

  <?php do_action('wps_products_heading_before', $data->query); ?>

  <h1 class="wps-products-heading <?= apply_filters('wps_products_heading_class', ''); ?>">
    <?= apply_filters('wps_products_heading', esc_html__('Products', 'wp-shopify')); ?>
  </h1>

  <?php do_action('wps_products_heading_after', $data->query); ?>

</header>
