<!--

Tab Content: Updates

-->
<div class="tab-content <?php echo $tab === 'updates' ? 'tab-content-active' : ''; ?>" data-tab-content="tab-updates">

  <div id="post-body" class="metabox-holder columns-2">

    <div id="post-body-content">

      <div class="meta-box-sortables ui-sortable">

        <?php

        require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-updates-license-activation.php';
        require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-updates-license-info.php';
        require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-updates-plugin-info.php';

        ?>

      </div>

    </div>

</div>

</div>
