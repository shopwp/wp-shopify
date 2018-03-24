<?php if (is_object($collection) && property_exists($collection, 'body_html')) { ?>

  <div
    itemprop="description"
    class="wps-collection-content <?php echo apply_filters('wps_collections_single_content_class', ''); ?>">
      <?php _e($collection->body_html, 'wp-shopify'); ?>
  </div>

<?php } ?>
