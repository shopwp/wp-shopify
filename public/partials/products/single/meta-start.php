<section
  class="wps-product-meta"
  data-product-price="<?php echo $product['variants'][0]['price']; ?>"
  data-product-quantity="1"
  data-product-variants-count="<?php echo count($product['variants']); ?>"
  data-product-post-id="<?php echo $product['details']['post_id']; ?>"
  data-product-id="<?php echo $product['details']['product_id']; ?>"
  data-product-selected-options=""
  data-product-selected-variant="<?php echo count($product['variants']) === 1 ? $product['variants'][0]['id'] : ''; ?>">
