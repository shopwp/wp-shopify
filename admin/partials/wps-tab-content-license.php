<!--

Tab Content: Updates

-->
<div class="tab-content <?php echo $active_tab === 'tab-license' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-license">

  <h3 class="wps-admin-section-heading">
    <span class="dashicons dashicons-download"></span>
    <?php esc_html_e('License Key Info', WPS_PLUGIN_TEXT_DOMAIN) ?>
  </h3>

  <div id="post-body" class="metabox-holder columns-2">

    <div id="post-body-content">

      <div class="meta-box-sortables ui-sortable">

        <?php


        require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-updates-pro.php';

        ?>

      </div>

    </div>

  </div>

</div>
