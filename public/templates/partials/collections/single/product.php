<?php

/*

@description   Single product on single collection page

@version       1.0.0
@since         1.0.49
@path          templates/partials/collections/single/product.php

@docs          https://wpshop.io/docs/templates/collections/single/product

*/

use WPS\DB\Images;

if ( !defined('ABSPATH') ) {
	exit;
}

$image = Images::get_image_details_from_product($data->product);

?>

<li
  itemscope
  itemtype="https://schema.org/Offer"
  class="wps-col wps-col-3 wps-collection-single-product">

  <a
    href="<?= esc_url( home_url() . '/products/' . $data->product->handle); ?>"
    class="wps-collections-product-link"
    title="<?php esc_attr_e($data->product->title . ' ' . $data->product->product_id . ' ' . $data->product->product_type, 'wp-shopify' ); ?>">

    <img
      itemprop="image"
      src="<?= esc_url($image->src); ?>" alt="<?php esc_attr_e($image->alt, 'wp-shopify'); ?>" class="wps-products-img" />

    <h2
      itemprop="name"
      class="wps-collections-product-title wps-products-title">
      <?php esc_html_e($data->product->title, 'wp-shopify'); ?>
    </h2>

    <h3 class="wps-products-price">
      <?= WPS\Utils::wps_format_money($data->product->variants[0]->price, $data->product->variants[0]); ?>
    </h3>

  </a>

</li>
