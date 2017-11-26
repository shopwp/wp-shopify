<div class="<?php echo apply_filters( 'wps_cart_class', '' ); ?> wps-cart">

  <?php do_action('wps_cart_before'); ?>

  <div class="wps-cart-section wps-cart-section--top">

    <h2 class="wps-cart-title">
      <?php echo apply_filters( 'wps_cart_title_text', 'Shopping cart' ); ?>
    </h2>

    <button class="wps-btn-close wps-modal-close-trigger" title="Open Cart">
      <span aria-role="hidden" class="wps-modal-close-trigger">
        <?php echo apply_filters( 'wps_cart_close_icon', '&times;' ); ?>
      </span>
    </button>

  </div>

  <div class="wps-cart-form">

    <div class="wps-cart-item-container wps-cart-section">
      <aside class="wps-cart-empty-notice"><h2>Your cart is empty</h2></aside>
    </div>

    <div class="wps-cart-bottom wps-row">
      <div class="wps-cart-info wps-clearfix wps-cart-section">

        <div class="wps-type--caps wps-cart-info__total">
          <?php echo apply_filters( 'wps_cart_total_text', 'Total' ); ?>
        </div>

        <div class="wps-cart-info__pricing">
          <span class="wps-pricing wps-pricing--no-padding"></span>
        </div>
      </div>

      <div class="wps-cart-actions-container wps-cart-section type--center">

        <div class="wps-cart-discount-notice wps-cart-info__small">
          <?php echo apply_filters( 'wps_cart_shipping_text', 'Shipping and discount codes are added at checkout.' ); ?>
        </div>

        <a href="https://checkout.shopify.com" class="wps-btn wps-btn-checkout" target="_self" title="Checkout" id="wps-btn-checkout">
          <?php echo apply_filters( 'wps_cart_checkout_text', 'Checkout' ); ?>
        </a>

      </div>

    </div>

  </div>

  <script id="wps-cart-item-template" type="text/template">

    <div class="wps-cart-item <?php echo apply_filters( 'wps_cart_item_class', '' ); ?>">

      <a href="#" class="wps-cart-item-img-link">
        <div class="wps-cart-item__img"></div>
      </a>

      <div class="wps-cart-item__content">

        <div class="wps-cart-item__content-row">
          <div class="wps-cart-item__variant-title"></div>
          <a href="#" class="wps-cart-item__title"></a>
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
