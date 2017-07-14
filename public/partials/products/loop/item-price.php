<?php

use WPS\DB\Variants;

?>

<h3 class="wps-products-price <?php echo apply_filters( 'wps_products_price_class', '' ); ?>">

  <?php

    // echo WPS\Utils::wps_format_money($product->price, $product);

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
        echo WPS\Utils::wps_format_money($firstVariantPrice, $product);

      } else {
        echo '<small class="wps-product-from-price">From: </small>' . WPS\Utils::wps_format_money($firstVariantPrice, $productNew) . ' <span class="wps-product-from-price-separator">-</span> ' . WPS\Utils::wps_format_money($lastVariantPrice, $productNew);

      }

    } else {

      echo WPS\Utils::wps_format_money($variants[0]['price'], $product);

    }

  ?>


</h3>
