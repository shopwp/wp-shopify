<?php

if ( !defined('ABSPATH') ) {
	exit;
}

$productImg = sprintf(
  __('<div class="%1$s-wrapper wps-col wps-col-%2$s"><img itemprop="image" src="%3$s" class="wps-product-gallery-img %4$s" alt="%5$s" data-wps-image-variants="%6$s"></div>'),
  $data->image_type_class,
  $data->amount_of_thumbs,
  esc_url($data->image_details->src),
  $data->image_type_class,
  $data->image_details->alt,
  $data->variant_ids
);

echo apply_filters('wps_product_img', $productImg, $data->product, $data->index);
