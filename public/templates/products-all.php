<?php

$args = !empty($shortcodeArgs) ? $shortcodeArgs : array();

if (empty($is_shortcode)) {
  get_header();
}

do_action(
  'wps_products_display',
  apply_filters('wps_products_args', $args),
  apply_filters('wps_products_custom_args', array())
);

if (empty($is_shortcode)) {
  get_sidebar();
  get_footer();
}
