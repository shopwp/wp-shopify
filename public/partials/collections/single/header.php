<header class="wps-collection-header <?php echo apply_filters('wps_collections_single_header_class', ''); ?>">

  <?php do_action('wps_collections_single_heading_before', $collection); ?>

  <?php if (is_object($collection) && property_exists($collection, 'title')) { ?>

    <h1 class="wps-collection-heading <?php echo apply_filters('wps_collections_single_heading_class', ''); ?>">
      <?php esc_html_e($collection->title, 'wp-shopify'); ?>
    </h1>
    
  <?php } ?>

  <?php do_action('wps_collection_single_img', $collection); ?>

  <?php do_action('wps_collections_single_heading_after', $collection); ?>

</header>
