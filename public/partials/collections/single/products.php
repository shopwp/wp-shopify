<section class="wps-collections-products <?php echo apply_filters('wps_collection_single_products_class', ''); ?>">

  <?php

  do_action('wps_collection_single_heading', $collection, $products);

  do_action('wps_collection_single_products_before', $collection, $products);
  do_action('wps_collection_single_products_list',  $collection, $products);
  do_action('wps_collection_single_products_after', $collection, $products);

  ?>

</section>
