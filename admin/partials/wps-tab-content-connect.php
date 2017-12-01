<!--

Tab Content: Connect

-->
<div class="tab-content <?php echo $tab === false ? 'tab-content-active' : ''; ?> <?php echo $connected ? 'wps-connected' : ''; ?>" data-tab-content="tab-connect">

  <div class="wps-admin-section-heading-group wps-l-row wps-l-space-between">

    <h2 class="wps-admin-section-heading wps-l-box-2">
      <span class="dashicons dashicons-update"></span> <?php esc_html_e( 'Connect and Sync ', 'wp-shopify' ); ?>
    </h2>

    <h3 class="wps-status-heading wps-admin-section-heading wps-l-box-2"><?php esc_html_e( 'Status:', 'wp-shopify' ); ?>

      <?php if($connected) { ?>
        <span class="wps-status is-connected"><?php esc_html_e('Connected', 'wp-shopify' ); ?></span>
      <?php } else { ?>
        <span class="wps-status is-disconnected"><?php esc_html_e('Disconnected', 'wp-shopify' ); ?></span>
      <?php } ?>

    </h3>

  </div>

  <div class="wps-admin-section">

    <p><?php printf(__('To connect your Shopify account, enter your unique buy button API key and Shopify domain below. Once you hit connect WP Shopify will redirect you to Shopify to verify the connection. If you need additional help, please see the <a href="%s" target="_blank"> video tutorial and documentation</a>.', 'wp-shopify'), esc_url($this->config->plugin_env . '/docs'));  ?></p>

    <form method="post" name="cleanup_options" action="options.php" id="wps-connect" class="wps-admin-form">

      <?php

        settings_fields($this->config->settings_connection_option_name);
        do_settings_sections($this->config->settings_connection_option_name);

        // Grab all connection settings
        $connection = $this->config->wps_get_settings_connection();

      ?>

      <!-- JS Access Token -->
      <div class="wps-form-group">

        <h3><?php esc_attr_e('Access Token', 'wp-shopify'); ?></h3>
        <input required <?php echo $connected ? 'disabled' : ''; ?> type="text" class="regular-text <?php echo $connected ? 'valid' : ''; ?>" id="<?php echo $this->config->settings_connection_option_name; ?>_js_access_token" name="js_access_token" value="<?php if(!empty($connection->js_access_token)) echo $connection->js_access_token; ?>" placeholder=""><div class="wps-form-icon wps-animated"></div>

      </div>

      <!-- My Shopify Domain -->
      <div class="wps-form-group">

        <h3><?php esc_attr_e( 'Domain', 'wp-shopify' ); ?> <small><?php esc_html_e('(example: yourshop.myshopify.com)', 'wp-shopify' ); ?></small></h3>
        <input required <?php echo $connected ? 'disabled' : ''; ?> type="text" class="regular-text <?php echo $connected ? 'valid' : ''; ?>" id="<?php echo $this->config->settings_connection_option_name; ?>_domain" name="domain" value="<?php if ( !empty($connection->domain) ) echo $connection->domain; ?>" placeholder="<?php esc_attr_e('shop.myshopify.com', 'wp-shopify' ); ?>" id="domain">
        <div class="wps-form-icon wps-animated"></div>

      </div>

      <!-- Nonce -->
      <input hidden type="text" class="regular-text" id="<?php echo $this->config->settings_connection_option_name; ?>_nonce_id" name="nonce" value="<?php echo wp_create_nonce( uniqid() ); ?>"/>

      <!-- App ID: -->
      <input hidden type="text" class="regular-text" id="<?php echo $this->config->settings_connection_option_name; ?>_app_id" name="app_id" value="6" />

      <!-- Webhook ID -->
      <input hidden type="text" class="regular-text" id="<?php echo $this->config->settings_connection_option_name; ?>_webhook_id" name="webhook_id" value="" />

      <!-- Shopify Access Token -->
      <input hidden type="text" class="regular-text" id="<?php echo $this->config->settings_connection_option_name; ?>_access_token" name="access_token" value="" />

      <!-- Submit -->
      <div class="wps-button-group button-group button-group-ajax">
        <?php if($connected) { ?>
          <?php submit_button(esc_html__('Disconnect Your Shopify Store', 'wp-shopify'), 'primary large', 'submitDisconnect', false, array()); ?>
        <?php } else { ?>
          <?php submit_button(esc_html__('Connect Your Shopify Store', 'wp-shopify'), 'primary large', 'submitConnect', false, array()); ?>
        <?php } ?>
        <div class="spinner"></div>

      </div>

    </form>

  </div>

</div>
