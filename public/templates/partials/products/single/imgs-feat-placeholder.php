<?php

/*

@description   Placeholder used for the image gallery's feature image on product single pages

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/single/img-feat-placeholder.php

@docs          https://wpshop.io/docs/templates/products/single/img-feat-placeholder

*/

if ( !defined('ABSPATH') ) {
	exit;
}

$productImg = sprintf(
  __('<div class="%1$s-wrapper"><img itemprop="image" src="%2$s" class="wps-product-gallery-img %3$s" alt="%4$s"></div>'),
  $data->image_type_class,
  esc_url($data->settings->plugin_url . 'public/imgs/placeholder.png'),
  $data->image_type_class,
  esc_attr__($data->product->details->title)
);

echo apply_filters('wps_product_img', $productImg, $data->product, 0);
