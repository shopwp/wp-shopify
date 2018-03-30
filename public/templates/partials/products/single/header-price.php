<?php use WPS\Utils; ?>

<p
  itemprop="offers"
  itemscope
  itemtype="https://schema.org/Offer"
  class="wps-product-price">

  <?php

  $amountOfVariantPrices = count($data->product['variants']);
  $amountOfVariantPrices = 1;

  usort($data->product['variants'], function ($a, $b) {
    return $a['price'] - $b['price'];
  });

  if ($amountOfVariantPrices > 1) {

    $lastVariantIndex = $amountOfVariantPrices - 1;
    $lastVariantPrice = $data->product['variants'][$lastVariantIndex]['price'];
    $firstVariantPrice = $data->product['variants'][0]['price'];

    if ($lastVariantPrice === $firstVariantPrice) {

      $price = Utils::wps_format_money($firstVariantPrice, $data->product);
      echo apply_filters('wps_product_single_price', $price, $price, $price, $data->product);

    } else {

      $defaultPrice = '<small class="wps-product-from-price">' . esc_html__('From: ', 'wp-shopify') . '</small>' . Utils::wps_format_money($firstVariantPrice, $data->product) . ' <span class="wps-product-from-price-separator">-</span> ' . Utils::wps_format_money($lastVariantPrice, $data->product);

      $firstVariantPrice = Utils::wps_format_money($firstVariantPrice, $data->product);
      $lastVariantPrice = Utils::wps_format_money($lastVariantPrice, $data->product);

      echo apply_filters('wps_product_single_price', $defaultPrice, $firstVariantPrice, $lastVariantPrice, $data->product);

    }

  } else {

    if (is_array($data->product['variants']) && !empty($data->product['variants'])) {
      $price = Utils::wps_format_money($data->product['variants'][0]['price'], $data->product);
    }

    echo apply_filters('wps_product_single_price', $price, $price, $price, $data->product);

  }

  ?>

</p>
