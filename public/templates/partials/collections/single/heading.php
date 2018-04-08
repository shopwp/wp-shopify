<?php

/*

@description   Heading for single collection

@version       1.0.0
@since         1.0.49
@path          templates/partials/collections/single/heading.php

@docs          https://wpshop.io/docs/templates/collections/single/heading

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<h1 class="wps-collection-heading <?= apply_filters('wps_collections_single_heading_class', ''); ?>">
  <?php esc_html_e($data->collection->title, 'wp-shopify'); ?>
</h1>
