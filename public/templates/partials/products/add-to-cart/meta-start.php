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
  class="wps-product-meta wps-is-disabled wps-is-loading <?php echo apply_filters('wps_product_single_meta_class', ''); ?>"
  data-product-price="<?php echo $data->product->variants[0]->price; ?>"
  data-product-quantity="1"
  data-product-variants-count="<?php echo count($data->product->variants); ?>"
  data-product-post-id="<?php echo $data->product->details->post_id; ?>"
  data-product-id="<?php echo $data->product->details->product_id; ?>"
  data-product-selected-options=""
  data-product-selected-variant="<?php echo count($data->product->variants) === 1 ? $data->product->variants[0]->id : ''; ?>"
  data-product-available-variants='<?php echo json_encode($data->filtered_options); ?>'>
