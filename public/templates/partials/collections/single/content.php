<?php

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<?php if (is_object($data->collection) && property_exists($data->collection, 'body_html')) { ?>

  <div
    itemprop="description"
    class="wps-collection-content <?php echo apply_filters('wps_collections_single_content_class', ''); ?>">
      <?php _e($data->collection->body_html, 'wp-shopify'); ?>
  </div>

<?php } ?>
