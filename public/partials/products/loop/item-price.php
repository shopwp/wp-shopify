<?php

use WPS\DB\Variants;
use WPS\Utils;

?>

<h3 class="wps-products-price <?php echo apply_filters( 'wps_products_price_class', '' ); ?>">

  <?php

    // echo Utils::wps_format_money($product->price, $product);

    $DB_Variants = new Variants();

    $variants = $DB_Variants->get_product_variants($product->post_id);
    $variants = json_decode(json_encode($variants), true);

    $productNew = array(
      'variants' => $variants
    );

    $amountOfVariantPrices = count($variants);

    usort($variants, function ($a, $b) {
      return $a['price'] - $b['price'];
    });


    if ($amountOfVariantPrices > 1) {

      $lastVariantIndex = $amountOfVariantPrices - 1;
      $lastVariantPrice = $variants[$lastVariantIndex]['price'];
      $firstVariantPrice = $variants[0]['price'];


      if ($lastVariantPrice === $firstVariantPrice) {

        $defaultPrice = Utils::wps_format_money($firstVariantPrice, $product);
        echo apply_filters('wps_products_price_one', $defaultPrice, $product);

      } else {

        $priceFirst = Utils::wps_format_money($firstVariantPrice, $product);
        $priceLast = Utils::wps_format_money($lastVariantPrice, $product);

        // echo 'First: ' . $priceFirst;
        // echo 'Last: ' . $priceLast;

        $defaultPrice = apply_filters('wps_products_price_multi_from', '<small class="wps-product-from-price">From: </small>') . apply_filters('wps_products_price_multi_first', $priceFirst) . apply_filters('wps_products_price_multi_separator', ' <span class="wps-product-from-price-separator">-</span> ') . apply_filters('wps_products_price_multi_last', $priceLast);

        echo apply_filters('wps_products_price_multi', $defaultPrice, $priceFirst, $priceLast, $product);

      }

    } else {

      $defaultPrice = Utils::wps_format_money($variants[0]['price'], $product);
      echo apply_filters('wps_products_price_one', $defaultPrice, $product);

    }

  ?>


</h3>
