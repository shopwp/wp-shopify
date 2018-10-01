<!--

Tab Content: Tools

-->
<div class="tab-content <?php echo $active_tab === 'tab-misc' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-misc">

  <h3 class="wps-admin-section-heading"><span class="dashicons dashicons-admin-tools"></span> <?php esc_html_e('Misc', WPS_PLUGIN_TEXT_DOMAIN); ?></h3>

  <div class="wps-admin-section">

    <h3><?php esc_attr_e('Migrate WP Shopify Database Tables', WPS_PLUGIN_TEXT_DOMAIN); ?> </h3>

    <p><?php esc_attr_e('If you just upgraded from a version below 1.2.2, then you need to perform a simple database upgrade. Please make sure that you\'ve made a backup of your database before proceeding. Data loss could occur!', WPS_PLUGIN_TEXT_DOMAIN); ?></p>

    <div class="wps-button-group button-group button-group-ajax" id="wps-button-wrapper-migrate">

      <?php

      $attributes = [
        'id' => 'wps-button-migrate'
      ];

      if ( get_site_option('wp_shopify_migration_needed') != true ) {
        $attributes['disabled'] = true;
      }

      ?>

      <?php submit_button( esc_html__('Upgrade Database Tables', WPS_PLUGIN_TEXT_DOMAIN), 'primary', 'submitURLs', false, $attributes) ; ?>

      <div class="spinner"></div>

    </div>

  </div>


</div>
