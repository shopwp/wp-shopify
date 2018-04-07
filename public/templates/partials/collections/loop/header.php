<?php

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<?php if (!is_single()) { ?>

  <header class="wps-collections-header wps-contain wps-row <?php echo apply_filters('wps_collections_header_class', '', $data->collections); ?>">

    <?php do_action('wps_collections_heading_before', $data->collections); ?>

    <h1 class="wps-collections-heading <?php echo apply_filters('wps_collections_heading_class', ''); ?>">
      <?php echo apply_filters('wps_collections_heading', esc_html__('Collections', 'wp-shopify'), $data->collections); ?>
    </h1>

    <?php do_action('wps_collections_heading_after', $data->collections); ?>

  </header>

<?php } ?>
