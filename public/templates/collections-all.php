<?php

$args = !empty($shortcodeArgs) ? $shortcodeArgs : array();

if (empty($is_shortcode)) {
  get_header();
}

do_action(
  'wps_collections_display',
  apply_filters('wps_collections_args', $args),
  apply_filters('wps_collections_custom_args', array())
);

if (empty($is_shortcode)) {

  do_action('wps_collections_sidebar');
  get_footer();
  
}
