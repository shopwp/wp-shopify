<?php use WPS\Utils; ?>

<p class="wps-product-price">

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
      echo Utils::wps_format_money($firstVariantPrice, $product);

    } else {
      echo '<small class="wps-product-from-price">From: </small>' . Utils::wps_format_money($firstVariantPrice, $product) . ' <span class="wps-product-from-price-separator">-</span> ' . Utils::wps_format_money($lastVariantPrice, $product);
    }

  } else {
    echo Utils::wps_format_money($product['variants'][0]['price'], $product);

  }

  ?>

</p>
