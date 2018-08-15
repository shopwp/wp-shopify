<?php

/*

@description   Used to display the majority of content for the 'collections all' page.

@version       1.0.0
@since         1.0.49
@path          templates/partials/collections/all-display.php
@partials      templates/partials/collections

@docs          https://wpshop.io/docs/templates/collections/all-display

*/

if ( !defined('ABSPATH') ) {
	exit;
}

do_action( 'wps_collections_header', $data->collections );
do_action( 'wps_collections_header_after', $data->collections );

do_action( 'wps_collections_before', $data->collections );

if (count($data->collections) > 0) {

  do_action( 'wps_collections_loop_start', $data->collections );

  foreach ($data->collections as $collection) {

    do_action( 'wps_collections_item_start', $collection, $data->args, $data->custom_args );
    do_action( 'wps_collections_item', $collection, $data->args );
    do_action( 'wps_collections_item_end', $collection );

  }

  do_action( 'wps_collections_loop_end', $data->collections );

  do_action( 'wps_collections_pagination_before', $data->collections );
  do_action( 'wps_collections_pagination', $data->query );
  do_action( 'wps_collections_pagination_after', $data->collections );

} else {
  do_action( 'wps_collections_no_results', $data->args );

}

do_action( 'wps_collections_after', $data->collections );
