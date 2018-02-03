<?php

use WPS\Utils;

$filteredOptions = Utils::filter_variants_to_options_values($product['variants']);

?>

<section
  class="wps-product-meta wps-is-disabled wps-is-loading"
  data-product-price="<?php echo $product['variants'][0]['price']; ?>"
  data-product-quantity="1"
  data-product-variants-count="<?php echo count($product['variants']); ?>"
  data-product-post-id="<?php echo $product['details']['post_id']; ?>"
  data-product-id="<?php echo $product['details']['product_id']; ?>"
  data-product-selected-options=""
  data-product-selected-variant="<?php echo count($product['variants']) === 1 ? $product['variants'][0]['id'] : ''; ?>"
  data-product-available-variants='<?php echo json_encode($filteredOptions); ?>'>
