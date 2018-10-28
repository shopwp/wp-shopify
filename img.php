<?php

/*

@description   Individual image used for the image gallery on product single pages

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/single/img.php

@docs          https://wpshop.io/docs/templates/products/single/img

*/

if ( !defined('ABSPATH') ) {
	exit;
}

$productImg = sprintf(
  __('<div class="%1$s-wrapper wps-col wps-col-%2$s"><img itemprop="image" src="%3$s" class="wps-product-gallery-img %4$s" alt="%5$s" data-wps-image-variants="%6$s"></div>'),
  $data->image_type_class,
  $data->amount_of_thumbs,
  esc_url( $data->custom_sizing ? $data->custom_image_src : $data->image_details->src ),
  $data->image_type_class,
  $data->image_details->alt,
  $data->variant_ids
);

echo apply_filters('wps_product_img', $productImg, $data->product, $data->index);
