<?php



?>

<h3
  itemprop="offers"
  itemscope
  itemtype="https://schema.org/Offer"
  class="wps-products-price <?php echo apply_filters( 'wps_products_price_class', '' ); ?>">

  <?php

    // echo Utils::wps_format_money($data->product->price, $data->product);

    $DB_Variants = new Variants();

    $variants = $DB_Variants->get_product_variants($data->product->post_id);
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

        $defaultPrice = Utils::wps_format_money($firstVariantPrice, $data->product);
        echo apply_filters('wps_products_price_one', $defaultPrice, $data->product);

      } else {

        $priceFirst = Utils::wps_format_money($firstVariantPrice, $data->product);
        $priceLast = Utils::wps_format_money($lastVariantPrice, $data->product);

        // echo 'First: ' . $priceFirst;
        // echo 'Last: ' . $priceLast;

        $defaultPrice = apply_filters('wps_products_price_multi_from', '<small class="wps-product-from-price">' . esc_html__('From: ', 'wp-shopify') . '</small>') . apply_filters('wps_products_price_multi_first', $priceFirst) . apply_filters('wps_products_price_multi_separator', ' <span class="wps-product-from-price-separator">-</span> ') . apply_filters('wps_products_price_multi_last', $priceLast);

        echo apply_filters('wps_products_price_multi', $defaultPrice, $priceFirst, $priceLast, $data->product);

      }

    } else {

      $defaultPrice = Utils::wps_format_money($variants[0]['price'], $data->product);
      echo apply_filters('wps_products_price_one', $defaultPrice, $data->product);

    }

  ?>

</h3>
