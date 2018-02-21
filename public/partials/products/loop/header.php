<?php if (!is_single()) { ?>

  <header class="wps-products-header wps-contain wps-row <?php echo apply_filters('wps_products_header_class', ''); ?>">

    <?php do_action('wps_products_heading_before', $query); ?>

    <h1 class="wps-products-heading <?php echo apply_filters('wps_products_heading_class', ''); ?>">
      <?php echo apply_filters('wps_products_heading', esc_html__('Products', 'wp-shopify')); ?>
    </h1>

    <?php do_action('wps_products_heading_after', $query); ?>

  </header>

<?php } ?>
