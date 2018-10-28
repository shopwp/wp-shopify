<?php

/*

@description   Collection image within loop

@version       1.0.0
@since         1.0.49
@path          templates/partials/collections/loop/item-img.php

@docs          https://wpshop.io/docs/templates/collections/loop/item-img

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<img
  itemprop="image"
  src="<?= esc_url( $data->custom_sizing ? $data->custom_image_src : $data->image->src ); ?>"
  alt="<?php esc_attr_e($data->image->alt); ?>"
  class="<?= apply_filters( 'wps_collections_img_class', '' ); ?>" />
