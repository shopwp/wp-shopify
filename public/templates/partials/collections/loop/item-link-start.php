<?php

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<a href="<?php echo esc_url( home_url() . '/' . $data->settings->url_collections . '/' . $data->collection->handle); ?>" class="wps-collection-link <?php echo apply_filters( 'wps_collections_link_class', '' ); ?>" title="<?php esc_attr_e($data->collection->title, 'wp-shopify' ); ?>">
