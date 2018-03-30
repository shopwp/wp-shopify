<?php

if (empty($data)) {

  $data = [
    'shortcodeArgs' => [],
    'is_shortcode' 	=> false
  ];

  $data = (object) $data;

}

$data->shortcodeArgs = !empty($data->shortcodeArgs) ? $data->shortcodeArgs : [];


if (empty($data->is_shortcode)) {
  get_header('wps');
}

do_action('wps_breadcrumbs');

do_action(
  'wps_collections_display',
  apply_filters('wps_collections_args', $data->shortcodeArgs),
  apply_filters('wps_collections_custom_args', array())
);

if (empty($data->is_shortcode)) {

  do_action('wps_collections_sidebar');
  get_footer('wps');

}
