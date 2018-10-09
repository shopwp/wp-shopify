<!--

Tab Content: Settings

-->

<div class="tab-content <?php echo $active_tab === 'tab-settings' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-settings">

  <ul class="subsubsub wps-submenu">

    <li>
      <a class="wps-sub-section-link current" href="#!" data-sub-section="wps-admin-section-general">General</a> |
    </li>

    <li>
      <a class="wps-sub-section-link" href="#!" data-sub-section="wps-admin-section-syncing">Syncing</a> |
    </li>

    <li>
      <a class="wps-sub-section-link" href="#!" data-sub-section="wps-admin-section-layout">Layout</a> |
    </li>

    <li>
      <a class="wps-sub-section-link" href="#!" data-sub-section="wps-admin-section-products">Products</a> |
    </li>

    <li>
      <a class="wps-sub-section-link" href="#!" data-sub-section="wps-admin-section-related-products">Related Products</a> |
    </li>

    <li>
      <a class="wps-sub-section-link" href="#!" data-sub-section="wps-admin-section-cart">Cart</a> |
    </li>

    <li>
      <a class="wps-sub-section-link" href="#!" data-sub-section="wps-admin-section-plugin">Plugin</a>
    </li>

  </ul>


  <form method="post" name="wps_settings_general" action="options.php" id="wps-settings" class="wps-admin-form">

    <!--

    General Settings

    -->
    <div class="wps-admin-sub-section is-active" id="wps-admin-section-general">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'General ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-product-urls.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-collections-urls.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-products-per-page.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-link-products-to-shopify.php';

          ?>

      </div>

    </div>


    <!--

    Syncing

    -->
    <div class="wps-admin-sub-section" id="wps-admin-section-syncing">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'Syncing ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php


          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-items-per-request.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-save-connection-only.php';



          ?>

      </div>

    </div>


    <!--

    Layout Settings

    -->
    <div class="wps-admin-sub-section" id="wps-admin-section-layout">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-layout"></span> <?php esc_html_e( 'General Layout ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-show-breadcrumbs.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-hide-pagination.php';
          // require_once plugin_dir_path( __FILE__ ) . 'settings/settings-pricing.php';

          ?>

      </div>

    </div>


    <!--

    Products Settings

    -->
    <div class="wps-admin-sub-section" id="wps-admin-section-products">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-tag"></span> <?php esc_html_e( 'Pricing ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-pricing.php';

          ?>

      </div>

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-art"></span> <?php esc_html_e( 'Colors ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

        <?php

            require_once plugin_dir_path( __FILE__ ) . 'settings/settings-add-to-cart-button-color.php';
            require_once plugin_dir_path( __FILE__ ) . 'settings/settings-variant-button-color.php';

        ?>

      </div>

    </div>


    <!--

    Related Products

    -->
    <div class="wps-admin-sub-section" id="wps-admin-section-related-products">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-networking"></span> <?php esc_html_e( 'Related Products ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>


      <?php

      require_once plugin_dir_path( __FILE__ ) . 'settings/settings-related-products-show.php';
      require_once plugin_dir_path( __FILE__ ) . 'settings/settings-related-products-sort.php';
      require_once plugin_dir_path( __FILE__ ) . 'settings/settings-related-products-amount.php';

      ?>


    </div>


    <!--

    Cart Settings

    -->
    <div class="wps-admin-sub-section" id="wps-admin-section-cart">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-cart"></span> <?php esc_html_e( 'Cart ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-load-cart.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-enable-cart-terms.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-cart-terms-content.php';

          ?>

      </div>


      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-art"></span> <?php esc_html_e( 'Colors ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

        <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-cart-checkout-button-color.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-cart-icon-color.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-cart-counter-color.php';

        ?>

      </div>

    </div>


    <!--

    Assets Settings

    -->
    <div class="wps-admin-sub-section" id="wps-admin-section-plugin">

      <h2 class="wps-admin-section-heading">
        <span class="dashicons dashicons-admin-plugins"></span> <?php esc_html_e( 'Plugin ', WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </h2>

      <div class="wps-admin-section">

          <?php

          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-load-styles.php';
          require_once plugin_dir_path( __FILE__ ) . 'settings/settings-beta-enable.php';

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
