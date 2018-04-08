<?php

/*

@description   Featured image used within the image gallery on product single pages

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/single/img-feat.php

@docs          https://wpshop.io/docs/templates/products/single/img-feat

*/

if ( !defined('ABSPATH') ) {
	exit;
}

$productImg = sprintf(
  __('<div class="%1$s-wrapper"><img itemprop="image" src="%2$s" class="wps-product-gallery-img %3$s" alt="%4$s" data-wps-image-variants="%5$s"></div>'),
  $data->image_type_class,
  esc_url($data->image_details->src),
  $data->image_type_class,
  esc_attr__($data->product->details->title),
  $data->variant_ids
);

echo apply_filters('wps_product_img', $productImg, $data->product, $data->index);
