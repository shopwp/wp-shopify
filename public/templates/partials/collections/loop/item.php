<?php

/*

@description   Each collection within loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/collections/loop/item.php

@docs          https://wpshop.io/docs/templates/collections/loop/item

*/

if ( !defined('ABSPATH') ) {
	exit;
}

do_action('wps_collections_item_before', $data->collection);
do_action('wps_collections_img_before', $data->collection);
do_action('wps_collections_img', $data->collection);
do_action('wps_collections_title_before', $data->collection);
do_action('wps_collections_title', $data->collection);
do_action('wps_collections_item_after', $data->collection);
