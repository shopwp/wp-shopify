<?php

if (count($data->product['options']) === 1) {

  if (count($data->product['variants']) > 1) {
    $col = 2;

  } else {
    $col = 1;
  }

} else if (count($data->product['options']) === 2) {
  $col = 1;

} else if (count($data->product['options']) === 3) {
  $col = 1;

} else {
  $col = 1;
}

?>

<div class="wps-btn-wrapper wps-col wps-col-<?php echo $col; ?> <?php echo apply_filters('wps_add_to_cart_wrapper_class', ''); ?>">

  <button
    itemprop="potentialAction"
    itemscope
    itemtype="https://schema.org/BuyAction"
    href="#!"
    class="wps-btn wps-col-1 wps-btn-secondary wps-add-to-cart <?php echo apply_filters('wps_add_to_cart_class', ''); ?>"
    title="<?php esc_attr_e('Add to cart', 'wp-shopify'); ?>">
    <?php esc_html_e('Add to cart', 'wp-shopify'); ?>
  </button>

</div>
