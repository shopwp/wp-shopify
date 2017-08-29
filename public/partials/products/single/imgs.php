<?php

  use WPS\Utils;

  $Utils = new Utils();

  usort($product['images'], array($Utils, "sort_product_images"));


  $i = 0;
  $len = count($product['images']);
  $amountOfThumbs = $len-1;

  foreach ($product['images'] as $key => $image) {

    if ($i === 0) {
      $typeClass = 'wps-product-gallery-img-feat';

      $productImg = '<div class="' . $typeClass . '-wrapper"><img src="' . $image['src'] . '" class="wps-product-gallery-img ' . $typeClass . '" alt="' . $product['details']['title'] . ' ' . $product['details']['product_id'] . ' Featured ' . $product['details']['product_type'] . '"></div>';

    } else {

      if($amountOfThumbs === 1) {
        $amountOfThumbs = 3;
      }

      $typeClass = 'wps-product-gallery-img-thumb';

      $productImg = '<div class="' . $typeClass . '-wrapper wps-col wps-col-' . $amountOfThumbs . '"><img src="' . $image['src'] . '" class="wps-product-gallery-img ' . $typeClass . '" alt="' . $product['details']['title'] . ' Thumbnail ' . $product['details']['product_type'] . '"></div>';

    }

  if($i === 0) {
    do_action('wps_before_first_product_img');
  }

  echo apply_filters('wps_product_img', $productImg, $product, $i);

  // Fires after the feature image but before the thumbnails
  if($i === 0) {
    do_action('wps_product_single_thumbs_start', $product);
  }

  // Fires after all the thumbnails
  if($i === $len - 1) {
    do_action('wps_product_single_thumbs_end', $product);
  }

  $i++;

}
