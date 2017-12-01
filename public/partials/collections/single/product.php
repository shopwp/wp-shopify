<?php

use WPS\DB\Images;

$image = Images::get_image_details_from_product($product);

?>

<li
  itemscope
  itemtype="https://schema.org/Offer"
  class="wps-col wps-col-3 wps-collection-single-product">

  <a
    href="<?php esc_url( home_url() . '/products/' . $product->handle); ?>"
    class="wps-collections-product-link"
    title="<?php esc_attr_e($product->title . ' ' . $product->product_id . ' ' . $product->product_type, 'wp-shopify' ); ?>">

    <img
      itemprop="image"
      src="<?php esc_url($image['src']); ?>" alt="<?php esc_attr_e($image['alt'], 'wp-shopify'); ?>" class="wps-products-img" />

    <h2
      itemprop="name"
      class="wps-collections-product-title wps-products-title">
      <?php esc_html_e($product->title, 'wp-shopify'); ?>
    </h2>

    <h3 class="wps-products-price">
      <?php echo WPS\Utils::wps_format_money($product->variants[0]->price, $product->variants[0]); ?>
    </h3>

  </a>

</li>
