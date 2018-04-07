<?php

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<section class="wps-collections-products <?php echo apply_filters('wps_collection_single_products_class', ''); ?>">

  <?php

  do_action('wps_collection_single_heading', $data->collection, $data->products);
  do_action('wps_collection_single_products_before', $data->collection, $data->products);
  do_action('wps_collection_single_products_list',  $data->collection, $data->products);
  do_action('wps_collection_single_products_after', $data->collection, $data->products);

  ?>

</section>
