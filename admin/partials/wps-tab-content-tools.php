<!--

Tab Content: Tools

-->
<div class="tab-content <?php echo $tab === 'tools' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-tools">

  <h3 class="wps-admin-section-heading"><span class="dashicons dashicons-admin-tools"></span> <?php esc_html_e('Tools', 'wp-shopify'); ?></h3>

  <div class="wps-admin-section">

    <h3><?php esc_attr_e('Manual Sync ', 'wp-shopify'); ?> <span class="wps-help-tip wps-help-tip-inline" title="<?php esc_attr_e("Note: To fix syncing issues you may want to ensure that the 'Webhooks callback URL' located on the Settings tab is set to a publicly accsible URL."); ?>"></span></h3>
    <p><?php esc_attr_e('If you\'re having trouble keeping WordPress in sync with Shopify you can manually resync here.', 'wp-shopify'); ?></p>

    <div class="wps-button-group button-group button-group-ajax <?php echo $connected ? 'wps-is-active' : 'wps-is-not-active'; ?>">

      <?php

      if($connected) {

        $props = array(
          'id'        => 'wps-button-sync'
        );

      } else {

        $props = array(
          'disabled'  => 'disabled',
          'id'        => 'wps-button-sync'
        );

      }

      submit_button(esc_html__('Sync Shopify', 'wp-shopify'), 'primary', 'submitURLs', false, $props); ?>

      <div class="spinner"></div>

    </div>

  </div>

  <div class="wps-admin-section">

    <h3><?php esc_html_e('Clear Cache', 'wp-shopify'); ?></h3>
    <p><?php esc_html_e('If you\'re noticing various changes not appearing, try clearing the transient cache here.', 'wp-shopify'); ?></p>

    <div class="wps-button-group button-group button-group-ajax wps-is-active">

      <?php

      $props = array(
        'id' => 'wps-button-clear-cache'
      );

      submit_button(esc_html__('Clear Cache', 'wp-shopify'), 'primary', 'submitURLs', false, $props); ?>

      <div class="spinner"></div>

    </div>

  </div>


</div>
