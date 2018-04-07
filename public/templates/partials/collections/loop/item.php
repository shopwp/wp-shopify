<?php

if ( !defined('ABSPATH') ) {
	exit;
}

do_action('wps_collections_item_before', $data->collection);
do_action('wps_collections_img_before', $data->collection);
do_action('wps_collections_img', $data->collection);
do_action('wps_collections_title_before', $data->collection);
do_action('wps_collections_title', $data->collection);
do_action('wps_collections_item_after', $data->collection);
