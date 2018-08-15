<!--

License Activation

-->
<div class="postbox wps-postbox-license-activation">

  <div class="handlediv" title="Click to toggle"><br></div>

  <div class="inside">

    <form method="post" name="cleanup_options" action="" id="wps-license" class="wps-admin-form">

      <?php

        settings_fields(WPS_SETTINGS_GENERAL_OPTION_NAME);
        do_settings_sections(WPS_SETTINGS_GENERAL_OPTION_NAME);

      ?>

      <!-- Nonce -->
      <input hidden type="text" class="regular-text" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_nonce_license_id" name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[nonce]" value="<?php echo wp_create_nonce( uniqid() ); ?>"/>

      <!-- License Key -->
      <div class="wps-form-group">

        <h3><?php esc_html_e('License Key', WPS_PLUGIN_TEXT_DOMAIN); ?></h3>

        <small class="wps-is-hidden">
          <?php printf(__('You can find your license key <a href="%1$s" target="_blank">within your account</a> or contained inside your payment confirmation email.', WPS_PLUGIN_TEXT_DOMAIN), esc_url("https://wpshop.io/login")); ?>
        </small>

        <input autocomplete="off" required <?php echo $activeLicense ? 'disabled' : ''; ?> type="text" class="regular-text wps-input-license-key <?php echo $activeLicense ? 'valid' : ''; ?> wps-is-hidden" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_license" name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[license_key]" value="<?php if(!empty($license->license_key)) echo $maskedKey; ?>" placeholder=""><div class="wps-form-icon wps-animated wps-is-hidden"></div>

      </div>

      <!-- Submit -->
      <div class="wps-button-group button-group button-group-ajax wps-is-hidden">

        <?php if($activeLicense) { ?>
          <?php submit_button(esc_html__('Deactivate License', WPS_PLUGIN_TEXT_DOMAIN), 'primary', 'submit-license', false, array('data-status' => 'deactivate')); ?>

        <?php } else { ?>
          <?php submit_button(esc_html__('Activate License', WPS_PLUGIN_TEXT_DOMAIN), 'primary', 'submit-license', false, array('data-status' => 'activate')); ?>

        <?php } ?>

        <div class="spinner"></div>

      </div>

      <div class="spinner"></div>


    </form>

  </div>

</div>
