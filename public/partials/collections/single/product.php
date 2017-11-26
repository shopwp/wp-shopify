<?php

use WPS\DB\Images;

$image = Images::get_image_details_from_product($product);

?>

<li
  itemscope
  itemtype="https://schema.org/Offer"
  class="wps-col wps-col-3 wps-collection-single-product">

  <a
    href="/products/<?php echo $product->handle; ?>"
    class="wps-collections-product-link"
    title="<?php echo $product->title . ' ' . $product->product_id . ' ' . $product->product_type; ?>">

    <img
      itemprop="image"
      src="<?php echo $image['src']; ?>" alt="<?php echo $image['alt']; ?>" class="wps-products-img" />

    <h2
      itemprop="name"
      class="wps-collections-product-title wps-products-title">
        <?php echo $product->title; ?>
    </h2>

    <h3 class="wps-products-price">
      <?php echo WPS\Utils::wps_format_money($product->variants[0]->price, $product->variants[0]); ?>
    </h3>

  </a>

</li>
