<?php

/*

@description   Product list on single collection page

@version       1.0.0
@since         1.0.49
@path          templates/partials/collections/single/products-list.php

@docs          https://wpshop.io/docs/templates/collections/single/products-list

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<ul class="wps-row wps-row-left wps-collections-products <?= apply_filters('wps_collection_single_products_list_class', ''); ?>">

  <?php foreach ($data->products as $key => $product) {

		$product->showing_compare_at = $data->showing_compare_at;
		$product->showing_local = $data->showing_local;

    do_action('wps_collection_single_product', $product);

  } ?>

</ul>
