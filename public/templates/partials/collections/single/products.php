<?php

/*

@description   Product list wrapper on single collection page

@version       1.0.0
@since         1.0.49
@path          templates/partials/collections/single/products.php

@docs          https://wpshop.io/docs/templates/collections/single/products

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<section class="wps-collections-products <?= apply_filters('wps_collection_single_products_class', ''); ?>">

  <?php

  do_action('wps_collection_single_products_heading', $data->collection, $data->products);
  do_action('wps_collection_single_products_list',  $data->collection, $data->products);
  do_action('wps_collection_single_products_after', $data->collection, $data->products);

  ?>

</section>
