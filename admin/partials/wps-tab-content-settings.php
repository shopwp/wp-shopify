<!--

Tab Content: Settings

-->

<div class="tab-content <?= $active_tab === 'tab-settings' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-settings">

  <ul class="subsubsub wps-submenu">

    <li>
      <a class="wps-sub-section-link <?= $active_sub_nav === 'wps-admin-section-general' ? 'current' : ''; ?>" href="#!" data-sub-section="wps-admin-section-general">General</a> |
    </li>

    <li>
      <a class="wps-sub-section-link <?= $active_sub_nav === 'wps-admin-section-syncing' ? 'current' : ''; ?>" href="#!" data-sub-section="wps-admin-section-syncing">Syncing</a> |
    </li>

    <li>
      <a class="wps-sub-section-link <?= $active_sub_nav === 'wps-admin-section-layout' ? 'current' : ''; ?>" href="#!" data-sub-section="wps-admin-section-layout">Layout</a> |
    </li>

    <li>
      <a class="wps-sub-section-link <?= $active_sub_nav === 'wps-admin-section-products' ? 'current' : ''; ?>" href="#!" data-sub-section="wps-admin-section-products">Products</a> |
    </li>

    <li>
      <a class="wps-sub-section-link <?= $active_sub_nav === 'wps-admin-section-collections' ? 'current' : ''; ?>" href="#!" data-sub-section="wps-admin-section-collections">Collections</a> |
    </li>

    <li>
      <a class="wps-sub-section-link <?= $active_sub_nav === 'wps-admin-section-related' ? 'current' : ''; ?>" href="#!" data-sub-section="wps-admin-section-related-products">Related Products</a> |
    </li>

    <li>
      <a class="wps-sub-section-link <?= $active_sub_nav === 'wps-admin-section-cart' ? 'current' : ''; ?>" href="#!" data-sub-section="wps-admin-section-cart">Cart</a> |
    </li>

    <li>
      <a class="wps-sub-section-link <?= $active_sub_nav === 'wps-admin-section-checkout' ? 'current' : ''; ?>" href="#!" data-sub-section="wps-admin-section-checkout">Checkout</a> |
    </li>

    <li>
      <a class="wps-sub-section-link <?= $active_sub_nav === 'wps-admin-section-plugin' ? 'current' : ''; ?>" href="#!" data-sub-section="wps-admin-section-plugin">Plugin</a>
    </li>

  </ul>


  <form method="post" name="wps_settings_general" action="options.php" id="wps-settings" class="wps-admin-form">

    <!--

    General Settings

    -->
    <div class="wps-admin-sub-section <?= $active_sub_nav === 'wps-admin-section-general' ? 'is-active' : ''; ?>" id="wps-admin-section-general">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'General ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/general/settings-general-product-urls.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/general/settings-general-collections-urls.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/general/settings-general-products-per-page.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/general/settings-general-link-products-to-shopify.php';

          ?>

      </div>

    </div>


    <!--

    Syncing

    -->
    <div class="wps-admin-sub-section <?= $active_sub_nav === 'wps-admin-section-syncing' ? 'is-active' : ''; ?>" id="wps-admin-section-syncing">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'Syncing ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php


          require_once plugin_dir_path( __FILE__ ) . 'settings/syncing/settings-syncing-items-per-request.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/syncing/settings-syncing-save-connection-only.php';



          ?>

      </div>

    </div>


    <!--

    Layout Settings

    -->
    <div class="wps-admin-sub-section <?= $active_sub_nav === 'wps-admin-section-layout' ? 'is-active' : ''; ?>" id="wps-admin-section-layout">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-layout"></span> <?php esc_html_e( 'General Layout ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/layout/settings-layout-show-breadcrumbs.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/layout/settings-layout-hide-pagination.php';
          // require_once plugin_dir_path( __FILE__ ) . 'settings/settings-pricing.php';

          ?>

      </div>

    </div>


    <!--

    Products Settings

    -->
    <div class="wps-admin-sub-section <?= $active_sub_nav === 'wps-admin-section-products' ? 'is-active' : ''; ?>" id="wps-admin-section-products">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-tag"></span> <?php esc_html_e( 'Pricing ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/products/settings-products-compare-at.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/products/settings-products-show-price-range.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/products/settings-products-pricing.php';

          ?>

      </div>

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-art"></span> <?php esc_html_e( 'Colors ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

        <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/products/settings-products-add-to-cart-button-color.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/products/settings-products-variant-button-color.php';

        ?>

      </div>

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-format-aside"></span> <?php esc_html_e( 'Content ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

        <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/products/settings-products-heading-toggle.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/products/settings-products-heading.php';

        ?>

      </div>


      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-format-image"></span> <?php esc_html_e( 'Images ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/products/settings-products-images-sizing-toggle.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/products/settings-products-images-sizing-width.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/products/settings-products-images-sizing-height.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/products/settings-products-images-sizing-crop.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/products/settings-products-images-sizing-scale.php';

          ?>

      </div>

    </div>


    <!--

    Collections Settings

    -->
    <div class="wps-admin-sub-section <?= $active_sub_nav === 'wps-admin-section-collections' ? 'is-active' : ''; ?>" id="wps-admin-section-collections">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-format-aside"></span> <?php esc_html_e( 'Content ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

        <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/collections/settings-collections-heading-toggle.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/collections/settings-collections-heading.php';

        ?>

      </div>


      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-format-image"></span> <?php esc_html_e( 'Images ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/collections/settings-collections-images-sizing-toggle.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/collections/settings-collections-images-sizing-width.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/collections/settings-collections-images-sizing-height.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/collections/settings-collections-images-sizing-crop.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/collections/settings-collections-images-sizing-scale.php';

          ?>

      </div>

    </div>


    <!--

    Related Products

    -->
    <div class="wps-admin-sub-section <?= $active_sub_nav === 'wps-admin-section-related-products' ? 'is-active' : ''; ?>" id="wps-admin-section-related-products">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-networking"></span> <?php esc_html_e( 'Related Products ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>


      <?php

      require_once plugin_dir_path( __FILE__ ) . 'settings/related-products/settings-related-products-show.php';
      require_once plugin_dir_path( __FILE__ ) . 'settings/related-products/settings-related-products-sort.php';
      require_once plugin_dir_path( __FILE__ ) . 'settings/related-products/settings-related-products-amount.php';

      ?>

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-format-aside"></span> <?php esc_html_e( 'Content ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

        <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/related-products/settings-related-products-heading-toggle.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/related-products/settings-related-products-heading.php';

        ?>

      </div>


      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-format-image"></span> <?php esc_html_e( 'Images ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/related-products/settings-related-products-images-sizing-toggle.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/related-products/settings-related-products-images-sizing-width.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/related-products/settings-related-products-images-sizing-height.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/related-products/settings-related-products-images-sizing-crop.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/related-products/settings-related-products-images-sizing-scale.php';

          ?>

      </div>

    </div>


    <!--

    Cart Settings

    -->
    <div class="wps-admin-sub-section <?= $active_sub_nav === 'wps-admin-section-cart' ? 'is-active' : ''; ?>" id="wps-admin-section-cart">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-cart"></span> <?php esc_html_e( 'Cart ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/cart/settings-cart-load-cart.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/cart/settings-cart-enable-terms.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/cart/settings-cart-terms-content.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/cart/settings-cart-show-fixed-cart-tab.php';

          ?>

      </div>


      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-art"></span> <?php esc_html_e( 'Colors ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

        <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/cart/settings-cart-checkout-button-color.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/cart/settings-cart-icon-color.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/cart/settings-cart-counter-color.php';

          require_once plugin_dir_path( __FILE__ ) . 'settings/cart/settings-cart-icon-fixed-color.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/cart/settings-cart-counter-fixed-color.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/cart/settings-cart-fixed-background-color.php';

        ?>

      </div>

    </div>


    <!--

    Checkout Settings

    -->
    <div class="wps-admin-sub-section <?= $active_sub_nav === 'wps-admin-section-checkout' ? 'is-active' : ''; ?>" id="wps-admin-section-checkout">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-cart"></span> <?php esc_html_e( 'Checkout ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/checkout/settings-checkout-enable-custom-checkout-domain.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/checkout/settings-checkout-button-target.php';

          ?>

      </div>

    </div>


    <!--

    Assets Settings

    -->
    <div class="wps-admin-sub-section <?= $active_sub_nav === 'wps-admin-section-plugin' ? 'is-active' : ''; ?>" id="wps-admin-section-plugin">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-admin-plugins"></span> <?php esc_html_e( 'Plugin ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/plugin/settings-plugin-load-styles.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/plugin/settings-plugin-beta-enable.php';

          ?>

      </div>

    </div>


    <!-- Nonce -->
    <input hidden type="text" class="regular-text" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_urls_nonce_id" name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_nonce]" value="<?php echo wp_create_nonce( uniqid() ); ?>"/>


    <!-- Submit -->
    <div class="wps-button-group button-group button-group-ajax">
      <?php submit_button(esc_html__('Save WP Shopify Settings', WPS_PLUGIN_TEXT_DOMAIN), 'primary', 'submitSettings', false, array()); ?>
      <div class="spinner"></div>
    </div>


  </form>

</div>
