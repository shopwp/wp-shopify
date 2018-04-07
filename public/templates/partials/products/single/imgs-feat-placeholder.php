<?php

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
