<?php

get_header();

$args = !empty($shortcodeArgs) ? $shortcodeArgs : array();

do_action(
  'wps_collections_display',
  apply_filters('wps_collections_args', $args),
  apply_filters('wps_collections_custom_args', array())
);

get_sidebar();
get_footer();
