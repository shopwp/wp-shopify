<!--

Tab Content: Settings

-->
<div class="tab-content <?php echo $tab === 'settings' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-settings">

  <h2 class="wps-admin-section-heading">
    <span class="dashicons dashicons-admin-generic"></span> <?php esc_attr_e( 'Settings ', 'wp_admin_style' ); ?>
  </h2>

  <div class="wps-admin-section">

    <p><a href="<?php echo $this->config->plugin_env; ?>/docs" target="_blank">How to display your products</a>.</p>

    <form method="post" name="wps_settings_general" action="options.php" id="wps-settings" class="wps-admin-form">

      <?php

        // Grab all general settings
        $general = $this->config->wps_get_settings_general();

      ?>

      <!-- URLs -->
      <div class="wps-form-group">

        <h4><?php esc_attr_e( 'Products URL', 'wp_admin_style' ); ?></h4>
        <span><?php echo get_home_url(); ?>/</span> <input required type="text" class="regular-text" id="<?php echo $this->config->settings_general_option_name; ?>_url_products" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_url_products]" value="<?php if(!empty($general->url_products)) echo $general->url_products; ?>" placeholder="products">

        <h4><?php esc_attr_e( 'Collections URL', 'wp_admin_style' ); ?></h4>
        <span><?php echo get_home_url(); ?>/</span> <input required type="text" class="regular-text" id="<?php echo $this->config->settings_general_option_name; ?>_url_collections" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_url_collections]" value="<?php if(!empty($general->url_collections)) echo $general->url_collections; ?>" placeholder="collections">

      </div>

      <!-- URLs -->
      <div class="wps-form-group">

        <h4><?php esc_attr_e( 'Webhooks callback URL', 'wp_admin_style' ); ?></h4>
        <small>(Needs to be publicly accesible. Can change to test webhooks during development on localhost.)</small>
        <input required type="text" class="regular-text" id="<?php echo $this->config->settings_general_option_name; ?>_url_webhooks" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_webhooks_products]" value="<?php if(!empty($general->url_webhooks)) echo $general->url_webhooks; ?>" placeholder="<?php echo get_home_url(); ?>">

      </div>

      <!-- Number of products -->
      <div class="wps-form-group">

        <?php

        $default_posts_per_page = get_option( 'posts_per_page' );

        ?>

        <h4><?php esc_attr_e( 'Products per page', 'wp_admin_style' ); ?></h4>
        <small>(Defaults to standard WordPress post count set in Settings - Reading - Blog pages show at most)</small>
        <input type="number" class="regular-text" id="<?php echo $this->config->settings_general_option_name; ?>_num_posts" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_num_posts]" value="<?php echo !empty($general->num_posts) ? $general->num_posts : $default_posts_per_page; ?>" placeholder="">

      </div>

      <!-- Nonce -->
      <input hidden type="text" class="regular-text" id="<?php echo $this->config->settings_general_option_name; ?>_urls_nonce_id" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_nonce]" value="<?php echo wp_create_nonce( uniqid() ); ?>"/>

      <!-- Submit -->
      <div class="wps-button-group button-group button-group-ajax">
        <?php submit_button(__('Update Settings', $this->config->settings_general_option_name), 'primary', 'submitURLs', false, array()); ?>
        <div class="spinner"></div>
      </div>

    </form>

  </div>

</div>
