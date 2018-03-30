<?php

use WPS\Utils;

$filteredOptions = Utils::filter_variants_to_options_values($data->product['variants']);

?>

<section
  class="wps-product-meta wps-is-disabled wps-is-loading <?php echo apply_filters('wps_product_single_meta_class', ''); ?>"
  data-product-price="<?php echo $data->product['variants'][0]['price']; ?>"
  data-product-quantity="1"
  data-product-variants-count="<?php echo count($data->product['variants']); ?>"
  data-product-post-id="<?php echo $data->product['details']['post_id']; ?>"
  data-product-id="<?php echo $data->product['details']['product_id']; ?>"
  data-product-selected-options=""
  data-product-selected-variant="<?php echo count($data->product['variants']) === 1 ? $data->product['variants'][0]['id'] : ''; ?>"
  data-product-available-variants='<?php echo json_encode($filteredOptions); ?>'>
