<?php

$wps_args = !empty($shortcodeArgs) ? $shortcodeArgs : array();

if (empty($is_shortcode)) {
  get_header('wps');
}

do_action('wps_breadcrumbs');

do_action(
  'wps_products_display',
  apply_filters('wps_products_args', $wps_args),
  apply_filters('wps_products_custom_args', array())
);

if (empty($is_shortcode)) {

  do_action('wps_products_sidebar');
  get_footer('wps');

}
