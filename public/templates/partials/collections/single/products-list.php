<?php

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<ul class="wps-row wps-row-left wps-collections-products <?php echo apply_filters('wps_collection_single_products_list_class', ''); ?>">

  <?php foreach ($data->products as $key => $product) {
    do_action('wps_collection_single_product', $product);
  } ?>

</ul>
