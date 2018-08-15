<?php

/*

@description   The main entry point for the 'collections all' page. Used internally by the custom post type archive
               template as well as the [wps_collections] shortcode

@version       1.0.1
@since         1.0.49
@path          templates/collections-all.php
@partials      templates/partials/collections

@docs          https://wpshop.io/docs/templates/collections-all

*/

if ( !defined('ABSPATH') ) {
	exit;
}

use WPS\Factories\Templates_Factory;

$Templates = Templates_Factory::build();


/*

The $data variable is passed to this file if called by a shortcode
and if present, contains the list of parameters.

*/
if (empty($data)) {
  $data = false;

} else {
  $data = $Templates->get_shortcode_data($data);

}


/*

Determines whether to call get_header('wps'). Will not execute if called via shortcode.

*/
$Templates->show_header($data);


/*

We should eventually wrap this around a conditional. Will show twice if
user outputs two shortcodes on the same page while breadcrumbs is turned on.

*/
do_action('wps_breadcrumbs', $data);


/*

Kicks off the main template rendering—begins in products/main.php

*/
do_action(
  'wps_collections_display',
  apply_filters('wps_collections_args', $data),
  apply_filters('wps_collections_custom_args', [])
);


/*

Determines whether to call get_footer('wps'). Will not execute if called via shortcode.

*/
$Templates->show_footer($data);


/*

Calls the filter 'wps_collections_show_sidebar' which allows to show / hide the sidebar

*/
do_action('wps_collections_sidebar');
