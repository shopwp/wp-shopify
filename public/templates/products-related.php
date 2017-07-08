<?php

global $post;

do_action('wps_products_related_before');
do_action('wps_products_related_start');
do_action('wps_products_related_heading_before');

do_action('wps_products_related_heading_start');
echo apply_filters('wps_products_related_heading', 'Related Products');
do_action('wps_products_related_heading_end');
do_action('wps_products_related_heading_end_after');

/*

Here we need to adjust the arguments array based on what the user wants to do. By default,
we'll select 4 random products to display (excluding the product currently shown). However
the developer may override this by passing in their own config.

*/

do_action(
  'wps_products_display',
  apply_filters('wps_products_related_args', array(), $post),
  apply_filters('wps_products_related_custom_args', array())
);

do_action('wps_products_related_end');
do_action('wps_products_related_after');
