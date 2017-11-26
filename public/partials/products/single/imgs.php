<?php

  use WPS\Utils;
  use WPS\Config;
  use WPS\DB\Images;

  $Config = new Config();
  $Utils = new Utils();

  usort($product['images'], array($Utils, "sort_product_images"));

  $i = 0;
  $len = count($product['images']);
  $amountOfThumbs = $len-1;

  /*

  TODO: Revist how this is done. Duplication and fragility going on here

  */
  if ($amountOfThumbs < 0) {

    $typeClass = 'wps-product-gallery-img-feat';

    $productImg = '<div class="' . $typeClass . '-wrapper"><img itemprop="image" src="' . $Config->plugin_url . 'public/imgs/placeholder.png' . '" class="wps-product-gallery-img ' . $typeClass . '" alt="' . $product['details']['title'] . '"></div>';

    echo apply_filters('wps_product_img', $productImg, $product, 0);

  } else {

    foreach ($product['images'] as $key => $image) {

      $image = Images::get_image_details_from_image($image);
      $variantIDs = Images::get_variants_from_image($image);

      if ($i === 0) {
        $typeClass = 'wps-product-gallery-img-feat';
        $productImg = '<div class="' . $typeClass . '-wrapper"><img itemprop="image" src="' . $image['src'] . '" class="wps-product-gallery-img ' . $typeClass . '" alt="' . $image['alt'] . '" data-wps-image-variants="' . $variantIDs . '"></div>';

      } else {

        if ($amountOfThumbs === 1) {
          $amountOfThumbs = 3;
        }

        if ($amountOfThumbs > 8) {
          $amountOfThumbs = 6;
        }

        $typeClass = 'wps-product-gallery-img-thumb';
        $productImg = '<div class="' . $typeClass . '-wrapper wps-col wps-col-' . $amountOfThumbs . '"><img itemprop="image" src="' . $image['src'] . '" class="wps-product-gallery-img ' . $typeClass . '" alt="' . $image['alt'] . '" data-wps-image-variants="' . $variantIDs . '"></div>';

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








  }
