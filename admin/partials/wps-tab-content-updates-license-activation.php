<!--

License Activation

-->
<div class="postbox wps-postbox-license-activation">

  <div class="handlediv" title="Click to toggle"><br></div>

  <div class="inside">

    <form method="post" name="cleanup_options" action="" id="wps-license" class="wps-admin-form">

      <?php

        settings_fields($this->config->settings_license_option_name);
        do_settings_sections($this->config->settings_license_option_name);

      ?>

      <!-- Nonce -->
      <input hidden type="text" class="regular-text" id="<?php echo $this->config->settings_license_option_name; ?>_nonce_license_id" name="<?php echo $this->config->settings_license_option_name; ?>[nonce]" value="<?php echo wp_create_nonce( uniqid() ); ?>"/>

      <!-- License Key -->
      <div class="wps-form-group">

        <h3><?php esc_html_e('License Key', 'wp-shopify'); ?></h3>

        <small class="wps-is-hidden">
          <?php printf(__('You can find your license key <a href="%1$s" target="_blank">within your account</a> or contained inside your payment confirmation email.', 'wp-shopify'), esc_url("https://wpshop.io/login")); ?>
        </small>

        <input autocomplete="off" required <?php echo $activeLicense ? 'disabled' : ''; ?> type="text" class="regular-text wps-input-license-key <?php echo $activeLicense ? 'valid' : ''; ?> wps-is-hidden" id="<?php echo $this->config->settings_license_option_name; ?>_license" name="<?php echo $this->config->settings_license_option_name; ?>[key]" value="<?php if(!empty($license->key)) echo $maskedKey; ?>" placeholder=""><div class="wps-form-icon wps-animated wps-is-hidden"></div>

      </div>

      <!-- Submit -->
      <div class="wps-button-group button-group button-group-ajax wps-is-hidden">

        <?php if($activeLicense) { ?>
          <?php submit_button(esc_html__('Deactivate License', 'wp-shopify'), 'primary', 'submit-license', false, array('data-status' => 'deactivate')); ?>

        <?php } else { ?>
          <?php submit_button(esc_html__('Activate License', 'wp-shopify'), 'primary', 'submit-license', false, array('data-status' => 'activate')); ?>

        <?php } ?>

        <div class="spinner"></div>

      </div>

      <div class="spinner"></div>

      <!--

      Test functions

      <div class="">
        <input type="submit" value="Deactivate License" class="button button-primary wps-btn-deactivate-license">
        <input type="submit" value="Check License" class="button button-primary wps-btn-check-license">
        <input type="submit" value="Get Product" class="button button-primary wps-btn-get-product">
      </div>

      -->

    </form>

  </div>

</div>
