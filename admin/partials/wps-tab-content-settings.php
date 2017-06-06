<!--

Tab Content: Settings

-->
<div class="tab-content <?php echo $tab === 'settings' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-settings">

  <h2 class="wps-admin-section-heading">
    <span class="dashicons dashicons-download"></span> <?php esc_attr_e( 'Settings ', 'wp_admin_style' ); ?>
  </h2>

  <div class="wps-admin-section">
    <form method="post" name="wps_settings_general" action="options.php" id="wps-settings" class="wps-admin-form">

      <?php

        // Grab all general settings
        $general = $this->config->wps_get_settings_general();

      ?>

      <!-- URLs -->
      <div class="wps-form-group">

        <h4><?php esc_attr_e( 'Products Base URL', 'wp_admin_style' ); ?></h4>
        <span><?php echo get_home_url(); ?>/</span> <input required type="text" class="regular-text" id="<?php echo $this->config->settings_general_option_name; ?>_url_products" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_url_products]" value="<?php if(!empty($general->url_products)) echo $general->url_products; ?>" placeholder="products">

        <h4><?php esc_attr_e( 'Collections Base URL', 'wp_admin_style' ); ?></h4>
        <span><?php echo get_home_url(); ?>/</span> <input required type="text" class="regular-text" id="<?php echo $this->config->settings_general_option_name; ?>_url_collections" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_url_collections]" value="<?php if(!empty($general->url_collections)) echo $general->url_collections; ?>" placeholder="collections">

      </div>

      <!-- URLs -->
      <div class="wps-form-group">

        <h4><?php esc_attr_e( 'Webhooks callback URL', 'wp_admin_style' ); ?></h4>
        <input required type="text" class="regular-text" id="<?php echo $this->config->settings_general_option_name; ?>_url_webhooks" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_webhooks_products]" value="<?php if(!empty($general->url_webhooks)) echo $general->url_webhooks; ?>" placeholder="<?php echo get_home_url(); ?>">

      </div>

      <!-- Number of products -->
      <div class="wps-form-group">

        <h4><?php esc_attr_e( 'Number of Products per page', 'wp_admin_style' ); ?></h4>
        <input type="number" class="regular-text" id="<?php echo $this->config->settings_general_option_name; ?>_num_posts" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_num_posts]" value="<?php if(!empty($general->num_posts)) echo $general->num_posts; ?>" placeholder="">

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
