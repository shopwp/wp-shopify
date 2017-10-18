<!--

Tab Content: Tools

-->
<div class="tab-content <?php echo $tab === 'tools' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-tools">

  <h3 class="wps-admin-section-heading"><span class="dashicons dashicons-admin-tools"></span> Tools</h3>

  <div class="wps-admin-section">

    <h3><?php esc_attr_e( 'Manual Sync ', 'wp_admin_style' ); ?> <span class="wps-help-tip wps-help-tip-inline" title="Note: To fix syncing issues you may want to ensure that the 'Webhooks callback URL' located on the Settings tab is set to a publicly accsible URL."></span></h3>
    <p>If you're having trouble keeping WordPress in sync with Shopify you can manually resync here.</p>

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

      submit_button(__('Sync Shopify', $this->config->settings_general_option_name), 'primary', 'submitURLs', false, $props); ?>

      <div class="spinner"></div>

    </div>

  </div>


  <div class="wps-admin-section">

    <h3><?php esc_attr_e( 'Clear Cache', 'wp_admin_style' ); ?></h3>
    <p>If you're noticing various changes not appearing, try clearing the transient cache here.</p>

    <div class="wps-button-group button-group button-group-ajax wps-is-active">

      <?php

      $props = array(
        'id' => 'wps-button-clear-cache'
      );

      submit_button(__('Clear Cache', $this->config->settings_general_option_name), 'primary', 'submitURLs', false, $props); ?>

      <div class="spinner"></div>

    </div>

  </div>


</div>
