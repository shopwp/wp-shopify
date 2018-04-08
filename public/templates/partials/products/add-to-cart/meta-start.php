<?php

/*

@description   Opening tag for the element wrapping the product quantity add to cart and variant selections. This
               also wraps the actions-groups container.

               ** Important ** This partial depends on WP Shopify JavaScript. Modifying could potentially break the
               add to cart functionality. Do not remove any data- attributes.

@version       1.0.0
@since         1.0.49
@path          templates/partials/products/add-to-cart/meta-end.php
@js            true

@docs          https://wpshop.io/docs/templates/partials/products/add-to-cart/meta-end

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<section
  class="wps-product-meta wps-is-disabled wps-is-loading <?= apply_filters('wps_product_single_meta_class', ''); ?>"
  data-product-price="<?= $data->product->variants[0]->price; ?>"
  data-product-quantity="1"
  data-product-variants-count="<?= count($data->product->variants); ?>"
  data-product-post-id="<?= $data->product->details->post_id; ?>"
  data-product-id="<?= $data->product->details->product_id; ?>"
  data-product-selected-options=""
  data-product-selected-variant="<?= count($data->product->variants) === 1 ? $data->product->variants[0]->id : ''; ?>"
  data-product-available-variants='<?= json_encode($data->filtered_options); ?>'>
