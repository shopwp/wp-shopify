<!--

Tab Content: Tools

-->
<div class="tab-content <?php echo $tab === 'tools' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-tools">

  <h3 class="wps-admin-section-heading"><span class="dashicons dashicons-admin-tools"></span> <?php esc_html_e('Tools', 'wp-shopify'); ?></h3>

  <div class="wps-admin-section">

    <h3><?php esc_attr_e('Resync Shopify', 'wp-shopify'); ?> <span class="wps-help-tip wps-help-tip-inline" title="<?php esc_attr_e("Note: To fix syncing issues you may want to ensure that the 'Webhooks callback URL' located on the Settings tab is set to a publicly accessible URL. Also be aware that this does not delete the custom post types or any custom fields you\'ve added."); ?>"></span></h3>
    <p><?php esc_attr_e('Manually resync your Shopify data.', 'wp-shopify'); ?></p>

    <div class="wps-button-group button-group button-group-ajax <?php echo $connected ? 'wps-is-active' : 'wps-is-not-active'; ?>">

      <?php

      if ($connected) {

        $props = array(
          'id'        => 'wps-button-sync'
        );

      } else {

        $props = array(
          'disabled'  => 'disabled',
          'id'        => 'wps-button-sync'
        );

      }

      submit_button(esc_html__('Resync Shopify data', 'wp-shopify'), 'primary', 'submitURLs', false, $props); ?>

      <div class="spinner"></div>

    </div>

  </div>


  <div class="wps-admin-section">

    <h3><?php esc_html_e('Clear Transients', 'wp-shopify'); ?></h3>
    <p><?php esc_html_e('If you\'re noticing various changes not appearing, try clearing the WP Shopify transient cache here.', 'wp-shopify'); ?></p>

    <div class="wps-button-group button-group button-group-ajax wps-is-active">

      <?php

      $props = array(
        'id' => 'wps-button-clear-cache'
      );

      submit_button(esc_html__('Clear WP Shopify Transient Cache', 'wp-shopify'), 'primary', 'submitURLs', false, $props); ?>

      <div class="spinner"></div>

    </div>

  </div>


  <div class="wps-admin-section">

    <h3><?php esc_html_e('Remove all synced data', 'wp-shopify'); ?></h3>
    <p><?php esc_html_e('This will remove all WP Shopify data from within WordPress. Nothing will be changed in Shopify. Useful for clearing out any lingering data without reinstalling the plugin. (Note: this can take up to 60 seconds if you have many products).', 'wp-shopify'); ?></p>

    <div class="wps-button-group button-group button-group-ajax wps-is-active">

      <?php

      $props = array(
        'id' => 'wps-button-clear-all-data'
      );

      submit_button(esc_html__('Remove all synced data from WordPress', 'wp-shopify'), 'primary', 'submitURLs', false, $props); ?>

      <div class="spinner"></div>

    </div>

  </div>

  <?php /* @if NODE_ENV='pro' */ ?>
  <div class="wps-admin-section">

    <h3><?php esc_html_e('Reconnect Webhooks', 'wp-shopify'); ?></h3>
    <p><?php esc_html_e('This will attempt to reconnect all Shopify webhooks. Useful if you notice your data not auto syncing correctly.', 'wp-shopify'); ?></p>

    <div class="wps-button-group button-group button-group-ajax <?php echo $connected ? 'wps-is-active' : 'wps-is-not-active'; ?>">

      <?php

      if ($connected) {

        $props = array(
          'id'        => 'wps-button-webhooks'
        );

      } else {

        $props = array(
          'disabled'  => 'disabled',
          'id'        => 'wps-button-webhooks'
        );

      }

      submit_button(esc_html__('Reconnect Webhooks', 'wp-shopify'), 'primary', 'submitURLs', false, $props); ?>

      <div class="spinner"></div>

    </div>

  </div>
  <?php /* @endif */ ?>

</div>
