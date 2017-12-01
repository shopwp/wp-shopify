<?php use WPS\Utils; ?>

<p
  itemprop="offers"
  itemscope
  itemtype="https://schema.org/Offer"
  class="wps-product-price">

  <?php

  $amountOfVariantPrices = count($product['variants']);

  usort($product['variants'], function ($a, $b) {
    return $a['price'] - $b['price'];
  });

  if ($amountOfVariantPrices > 1) {

    $lastVariantIndex = $amountOfVariantPrices - 1;
    $lastVariantPrice = $product['variants'][$lastVariantIndex]['price'];
    $firstVariantPrice = $product['variants'][0]['price'];

    if ($lastVariantPrice === $firstVariantPrice) {

      $price = Utils::wps_format_money($firstVariantPrice, $product);

      echo apply_filters('wps_product_single_price', $price, $price, $price, $product);

    } else {

      $defaultPrice = '<small class="wps-product-from-price">' . esc_html__('From: ', 'wp-shopify') . '</small>' . Utils::wps_format_money($firstVariantPrice, $product) . ' <span class="wps-product-from-price-separator">-</span> ' . Utils::wps_format_money($lastVariantPrice, $product);

      $firstVariantPrice = Utils::wps_format_money($firstVariantPrice, $product);
      $lastVariantPrice = Utils::wps_format_money($lastVariantPrice, $product);

      echo apply_filters('wps_product_single_price', $defaultPrice, $firstVariantPrice, $lastVariantPrice, $product);

    }

  } else {

    $price = Utils::wps_format_money($product['variants'][0]['price'], $product);

    echo apply_filters('wps_product_single_price', $price, $price, $price, $product);

  }

  ?>

</p>
