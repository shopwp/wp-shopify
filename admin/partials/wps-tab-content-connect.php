<!--

Tab Content: Connect

-->
<div class="tab-content <?php echo $tab === false ? 'tab-content-active' : ''; ?> <?php echo $connected ? 'wps-connected' : ''; ?>" data-tab-content="tab-connect">

  <div class="wps-admin-section-heading-group wps-l-row wps-l-space-between">

    <h2 class="wps-admin-section-heading wps-l-box-2">
      <span class="dashicons dashicons-download"></span> <?php esc_attr_e( 'Connect and Sync ', 'wp_admin_style' ); ?>
    </h2>

    <h3 class="wps-status-heading wps-admin-section-heading wps-l-box-2">Status:
      <?php if($connected) { ?>
        <span class="wps-status is-connected">Connected</span>
      <?php } else { ?>
        <span class="wps-status is-disconnected">Disconnected</span>
      <?php } ?>
    </h3>

  </div>

  <div class="wps-admin-section">

    <p>To connect your Shopify account, enter your unique buy button API key and Shopify domain below. Once you hit connect WP Shopify will redirect you to Shopify to verify the connection. If you need additional help, please see the <a href="<?php echo $this->config->plugin_env; ?>/docs" target="_blank">video tutorial and documentation</a>.</p>

    <form method="post" name="cleanup_options" action="options.php" id="wps-connect" class="wps-admin-form">

      <?php

        settings_fields($this->config->settings_connection_option_name);
        do_settings_sections($this->config->settings_connection_option_name);

        // Grab all connection settings
        $connection = $this->config->wps_get_settings_connection();

        // echo "<pre>";
        // print_r($connection);
        // echo "</pre>";

      ?>

      <!-- JS Access Token -->
      <div class="wps-form-group">

        <h3><?php esc_attr_e( 'Access Token', 'wp_admin_style' ); ?></h3>
        <input required <?php echo $connected ? 'disabled' : ''; ?> type="text" class="regular-text <?php echo $connected ? 'valid' : ''; ?>" id="<?php echo $this->config->settings_connection_option_name; ?>_js_access_token" name="js_access_token" value="<?php if(!empty($connection->js_access_token)) echo $connection->js_access_token; ?>" placeholder=""><div class="wps-form-icon wps-animated"></div>

      </div>

      <!-- My Shopify Domain -->
      <div class="wps-form-group">

        <h3><?php esc_attr_e( 'Domain', 'wp_admin_style' ); ?> <small>(example: yourshop.myshopify.com)</small></h3>
        <input required <?php echo $connected ? 'disabled' : ''; ?> type="text" class="regular-text <?php echo $connected ? 'valid' : ''; ?>" id="<?php echo $this->config->settings_connection_option_name; ?>_domain" name="domain" value="<?php if(!empty($connection->domain)) echo $connection->domain; ?>" placeholder="shop.myshopify.com" id="domain">
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
          <?php submit_button(__('Disconnect your Shopify Account', $this->config->settings_connection_option_name), 'primary large', 'submitDisconnect', false, array()); ?>
        <?php } else { ?>
          <?php submit_button(__('Connect your Shopify Account', $this->config->settings_connection_option_name), 'primary large', 'submitConnect', false, array()); ?>
        <?php } ?>
        <div class="spinner"></div>
      </div>

    </form>

  </div>

</div>
