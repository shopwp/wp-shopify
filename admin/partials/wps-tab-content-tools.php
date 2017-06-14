<!--

Tab Content: Tools

-->
<div class="tab-content <?php echo $tab === 'tools' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-tools">

  <h3 class="wps-admin-section-heading"><span class="dashicons dashicons-admin-tools"></span> Tools</h3>

  <div class="wps-admin-section">

    <h3><?php esc_attr_e( 'Manual Sync ', 'wp_admin_style' ); ?></h3>

    <p>Mauris sollicitudin fermentum libero. Fusce egestas elit eget lorem. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Praesent congue erat at massa. Quisque libero metus, condimentum nec, tempor a, commodo mollis, magna.</p>

    <div class="wps-button-group button-group button-group-ajax">
      <?php submit_button(__('Sync store data', $this->config->settings_general_option_name), 'primary', 'submitURLs', false, array('class' => 'button wps-btn-sync-products')); ?>
      <div class="spinner"></div>
    </div>

  </div>

  <div class="wps-admin-section">

    <h3><?php esc_attr_e( 'Remove Store Data', 'wp_admin_style' ); ?></h3>

    <p>Mauris sollicitudin fermentum libero. Fusce egestas elit eget lorem. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Praesent congue erat at massa. Quisque libero metus, condimentum nec, tempor a, commodo mollis, magna.</p>

    <div class="wps-button-group button-group button-group-ajax">
      <?php submit_button(__('Remove store data', $this->config->settings_general_option_name), 'primary', 'wps-btn-uninstall', false, array()); ?>
      <div class="spinner"></div>
    </div>

  </div>

</div>
