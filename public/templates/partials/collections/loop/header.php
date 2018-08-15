<?php

/*

@description   Collections header within loop

@version       1.0.1
@since         1.0.49
@path          templates/partials/collections/loop/header.php

@docs          https://wpshop.io/docs/templates/collections/loop/header

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<?php if (!is_single()) { ?>

  <header class="wps-collections-header wps-contain wps-row <?= apply_filters('wps_collections_header_class', '', $data->collections); ?>">

    <?php do_action('wps_collections_heading_before', $data->collections); ?>

    <h1 class="wps-collections-heading <?= apply_filters('wps_collections_heading_class', ''); ?>">
      <?= apply_filters('wps_collections_heading', esc_html__('Collections', WPS_PLUGIN_TEXT_DOMAIN), $data->collections); ?>
    </h1>

    <?php do_action('wps_collections_heading_after', $data->collections); ?>

  </header>

<?php } ?>
