<?php

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<li
  itemprop="offers"
  itemscope=""
  itemtype="https://schema.org/Offer"
  class="wps-collection-item wps-collection-item-id-<?php echo $data->collection->collection_id; ?> wps-col wps-col-center wps-col-<?php echo apply_filters( 'wps_collections_per_row', $data->custom_args['items_per_row'] ); ?> <?php echo apply_filters('wps_collection_class', ''); ?>">

    <div class="wps-box">
