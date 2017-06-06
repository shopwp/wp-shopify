<?php

get_header();

$args = !empty($shortcodeArgs) ? $shortcodeArgs : array();

do_action(
  'wps_products_display',
  apply_filters('wps_products_args', $args),
  apply_filters('wps_products_custom_args', array())
);

get_sidebar();
get_footer();
