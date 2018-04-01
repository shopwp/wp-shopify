<div class="wps-btn-wrapper wps-col wps-col-<?php echo $data->button_width; ?> <?php echo apply_filters('wps_add_to_cart_wrapper_class', ''); ?>">

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
