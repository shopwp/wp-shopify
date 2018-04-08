<?php

/*

@description   Cart template. The actual markup that appears when opening the cart.

@version       1.0.0
@since         1.0.49
@path          templates/partials/cart/cart.php

@docs          https://wpshop.io/docs/templates/cart/cart

*/

if ( !defined('ABSPATH') ) {
	exit;
}

?>

<div class="<?= apply_filters( 'wps_cart_class', '' ); ?> wps-cart">

  <?php do_action('wps_cart_before'); ?>

  <div class="wps-cart-section wps-cart-section--top">

    <h2 class="wps-cart-title">
      <?= apply_filters('wps_cart_title_text', 'Shopping cart'); ?>
    </h2>

    <button class="wps-btn-close wps-modal-close-trigger" title="Open Cart">
      <span aria-role="hidden" class="wps-modal-close-trigger">
        <?= apply_filters('wps_cart_close_icon', '&times;'); ?>
      </span>
    </button>

  </div>

  <div class="wps-cart-form">

    <div class="wps-cart-item-container wps-cart-section">
      <aside class="wps-cart-empty-notice"><h2><?php esc_html_e('Your cart is empty', 'wp-shopify'); ?></h2></aside>
    </div>

    <div class="wps-cart-bottom wps-row">

      <div class="wps-cart-info wps-clearfix wps-cart-section">

        <div class="wps-type--caps wps-cart-info__total">
          <?= apply_filters('wps_cart_total_text', esc_html__('Total', 'wp-shopify')); ?>
        </div>

        <div class="wps-cart-info__pricing">
          <span class="wps-pricing wps-pricing--no-padding"></span>
        </div>
      </div>

      <div class="wps-cart-actions-container wps-cart-section type--center">

        <div class="wps-cart-discount-notice wps-cart-info__small">
          <?= apply_filters('wps_cart_shipping_text', esc_html__('Shipping and discount codes are added at checkout.', 'wp-shopify')); ?>
        </div>

        <?php

        do_action('wps_cart_checkout_btn_before');
        do_action('wps_cart_checkout_btn');
        do_action('wps_cart_checkout_btn_after');

        ?>

      </div>

    </div>

  </div>

  <script id="wps-cart-item-template" type="text/template">

    <div class="wps-cart-item <?= apply_filters( 'wps_cart_item_class', '' ); ?>">

      <a href="#!" class="wps-cart-item-img-link">
        <div class="wps-cart-item__img"></div>
      </a>

      <div class="wps-cart-item__content">

        <div class="wps-cart-item__content-row">
          <div class="wps-cart-item__variant-title"></div>
          <a href="#!" class="wps-cart-item__title"></a>
        </div>

        <div class="wps-cart-item__content-row">
          <div class="wps-cart-item__quantity-container">

            <button class="wps-btn--seamless wps-quantity-decrement" type="button">
              <span>-</span>
              <span class="wps-visuallyhidden"></span>
            </button>

            <input class="wps-cart-item__quantity" type="number" min="0" aria-label="Quantity">

            <button class="btn--seamless wps-quantity-increment" type="button">
              <span>+</span>
              <span class="wps-visuallyhidden"></span>
            </button>

          </div>

          <span class="wps-cart-item__price"></span>

        </div>

      </div>

    </div>

  </script>

  <?php do_action('wps_cart_after'); ?>

</div>
