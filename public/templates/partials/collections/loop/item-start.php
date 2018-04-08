<?php

/*

@description   Opening tags for each collection within loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/collections/loop/item-start.php

@docs          https://wpshop.io/docs/templates/collections/loop/item-start

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<li
  itemprop="offers"
  itemscope=""
  itemtype="https://schema.org/Offer"
  class="wps-collection-item wps-collection-item-id-<?= $data->collection->collection_id; ?> wps-col wps-col-center wps-col-<?= $data->items_per_row; ?> <?= apply_filters('wps_collection_class', ''); ?>">

    <div class="wps-box">
