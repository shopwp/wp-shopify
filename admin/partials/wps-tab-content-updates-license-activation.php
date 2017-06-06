<!--

License Activation

-->
<div class="postbox wps-postbox-license-activation">

  <div class="handlediv" title="Click to toggle"><br></div>

  <h2 class="hndle"><span><?php esc_attr_e( 'License Key Activation', 'wp_admin_style' ); ?></span></h2>

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

        <h3><?php esc_attr_e( 'License Key', 'wp_admin_style' ); ?></h3>
        <input autocomplete="off" required <?php echo $activeLicense ? 'disabled' : ''; ?> type="text" class="regular-text wps-input-license-key <?php echo $activeLicense ? 'valid' : ''; ?>" id="<?php echo $this->config->settings_license_option_name; ?>_license" name="<?php echo $this->config->settings_license_option_name; ?>[key]" value="<?php if(!empty($license->key)) echo $maskedKey; ?>" placeholder=""><div class="wps-form-icon wps-animated"></div>

      </div>

      <!-- Submit -->
      <div class="wps-button-group button-group button-group-ajax">

        <?php if($activeLicense) { ?>
          <?php submit_button(__('Deactivate License', $this->config->settings_license_option_name), 'primary', 'submit-license', false, array('data-status' => 'deactivate')); ?>
        <?php } else { ?>
          <?php submit_button(__('Activate License', $this->config->settings_license_option_name), 'primary', 'submit-license', false, array('data-status' => 'activate')); ?>
        <?php } ?>

        <div class="spinner"></div>
      </div>

    <!-- <div class="">
      <input type="submit" value="Deactivate License" class="button button-primary wps-btn-deactivate-license">
      <input type="submit" value="Check License" class="button button-primary wps-btn-check-license">
      <input type="submit" value="Get Product" class="button button-primary wps-btn-get-product">
    </div> -->

    </form>

  </div>

</div>
