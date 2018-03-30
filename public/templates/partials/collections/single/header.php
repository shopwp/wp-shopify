<header class="wps-collection-header <?php echo apply_filters('wps_collections_single_header_class', ''); ?>">

  <?php

  do_action('wps_collections_single_heading_before', $data->collection);

  if (is_object($data->collection) && property_exists($data->collection, 'title')) {
    do_action('wps_collection_single_heading', $data->collection);
  }

  do_action('wps_collection_single_img', $data->collection);
  do_action('wps_collections_single_heading_after', $data->collection);

  ?>

</header>
