<!--

Tab Content: Settings

-->

<?php

  // Grab all general settings
  $general = $this->config->wps_get_settings_general();

?>

<div class="tab-content tab-content-full <?php echo $tab === 'settings' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-settings">

  <h2 class="wps-admin-section-heading">
    <span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e( 'Settings ', 'wp-shopify' ); ?>
  </h2>

  <div class="wps-admin-section">

    <form method="post" name="wps_settings_general" action="options.php" id="wps-settings" class="wps-admin-form">

      <?php

      require_once plugin_dir_path( __FILE__ ) . 'settings/settings-product-urls.php';
      require_once plugin_dir_path( __FILE__ ) . 'settings/settings-collections-urls.php';
      require_once plugin_dir_path( __FILE__ ) . 'settings/settings-products-per-page.php';


      require_once plugin_dir_path( __FILE__ ) . 'settings/settings-products-sync-image-alt.php';
      require_once plugin_dir_path( __FILE__ ) . 'settings/settings-load-cart.php';
      require_once plugin_dir_path( __FILE__ ) . 'settings/settings-load-styles.php';


      require_once plugin_dir_path( __FILE__ ) . 'settings/settings-price-formatter.php';

      require_once plugin_dir_path( __FILE__ ) . 'settings/settings-link-products-to-shopify.php';
      require_once plugin_dir_path( __FILE__ ) . 'settings/settings-show-breadcrumbs.php';
      require_once plugin_dir_path( __FILE__ ) . 'settings/settings-hide-pagination.php';

      ?>

      <!-- Nonce -->
      <input hidden type="text" class="regular-text" id="<?php echo $this->config->settings_general_option_name; ?>_urls_nonce_id" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_nonce]" value="<?php echo wp_create_nonce( uniqid() ); ?>"/>

      <!-- Submit -->
      <div class="wps-button-group button-group button-group-ajax">
        <?php submit_button(esc_html__('Update Settings', 'wp-shopify'), 'primary', 'submitURLs', false, array()); ?>
        <div class="spinner"></div>
      </div>

    </form>

  </div>

</div>
