<?php

/*

@description   Collections header within loop

@version       1.0.2
@since         1.0.49
@path          templates/partials/collections/loop/header.php

@docs          https://wpshop.io/docs/templates/collections/loop/header

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<?php if ( !is_singular(WPS_COLLECTIONS_POST_TYPE_SLUG) ) { ?>

  <header class="wps-collections-header wps-contain wps-row <?= apply_filters('wps_collections_header_class', '', $data->collections); ?>">

    <?php do_action('wps_collections_heading_before', $data->collections); ?>

		<?php if ( apply_filters('wps_collections_heading_show', true) ) { ?>

			<h1 class="wps-collections-heading <?= apply_filters('wps_collections_heading_class', ''); ?>">
	      <?= $data->heading; ?>
	    </h1>

		<?php } ?>

    <?php do_action('wps_collections_heading_after', $data->collections); ?>

  </header>

<?php } ?>
