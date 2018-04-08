<?php

/*

@description   Title for each collection within loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/collections/loop/item-title.php

@docs          https://wpshop.io/docs/templates/collections/loop/item-title

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<h2
  itemprop="category"
  class="wps-collections-title <?= apply_filters( 'wps_collections_title_class', '' ); ?>">
  <?php esc_html_e($data->collection->title, 'wp-shopify'); ?>
</h2>
