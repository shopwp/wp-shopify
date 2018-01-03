<?php

  use WPS\Utils;
  use WPS\Config;
  use WPS\DB\Images;

  $Config = new Config();
  $Utils = new Utils();

  usort($product['images'], array($Utils, "sort_product_images"));


  $i = 0;
  $len = count($product['images']);
  $amountOfThumbs = $len;

  /*

  TODO: Revist how this is done. Duplication and fragility going on here

  */
  if ($amountOfThumbs < 1) {

    $typeClass = 'wps-product-gallery-img-feat';

    $productImg = sprintf(
      __('<div class="%1$s-wrapper"><img itemprop="image" src="%2$s" class="wps-product-gallery-img %3$s" alt="%4$s"></div>'),
      $typeClass,
      esc_url($Config->plugin_url . 'public/imgs/placeholder.png'),
      $typeClass,
      esc_attr__($product['details']['title'])
    );

    echo apply_filters('wps_product_img', $productImg, $product, 0);

  } else {

    foreach ($product['images'] as $key => $image) {

      $image = Images::get_image_details_from_image($image, $product);
      $variantIDs = Images::get_variants_from_image($image);

      if ($i === 0) {

        $typeClass = 'wps-product-gallery-img-feat';

        $productImg = sprintf(
          __('<div class="%1$s-wrapper"><img itemprop="image" src="%2$s" class="wps-product-gallery-img %3$s" alt="%4$s" data-wps-image-variants="%5$s"></div>'),
          $typeClass,
          esc_url($image['src']),
          $typeClass,
          esc_attr__($product['details']['title']),
          $variantIDs
        );

      } else {


        if ($amountOfThumbs === 1) {
          $amountOfThumbs = 3;
        }

        if ($amountOfThumbs > 8) {
          $amountOfThumbs = 6;
        }

        $typeClass = 'wps-product-gallery-img-thumb';

        $productImg = sprintf(
          __('<div class="%1$s-wrapper wps-col wps-col-%2$s"><img itemprop="image" src="%3$s" class="wps-product-gallery-img %4$s" alt="%5$s" data-wps-image-variants="%6$s"></div>'),
          $typeClass,
          $amountOfThumbs,
          esc_url($image['src']),
          $typeClass,
          $image['alt'],
          $variantIDs
        );

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
