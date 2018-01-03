<!--

Plugin Info

-->
<?php

// $plugin_data_latest = $License->wps_get_latest_plugin_version();
$plugin_data_latest = false;

?>

<div class="postbox wps-postbox-plugin-info" id="wps-plugin-info">

  <div class="spinner"></div>

  <h3><?php esc_html_e('Plugin Information', 'wp-shopify'); ?></h3>

  <table class="form-table wps-is-hidden">

    <tr valign="top">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Name', 'wp-shopify'); ?>
        </label>
      </td>

      <td class="wps-col wps-col-plugin-name">WP Shopify</td>

    </tr>

    <tr valign="top" class="alternate">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Tested up to WordPress', 'wp-shopify'); ?>
        </label>
      </td>

      <td class="wps-col wps-col-tested-up-to">
        <?php echo get_bloginfo('version'); ?>
      </td>

    </tr>

    <tr valign="top">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Installed version', 'wp-shopify'); ?>
        </label>
      </td>

      <td class="wps-col wps-col-plugin-ver">
        <?php esc_html_e($plugin_current_version, 'wp-shopify' ); ?>
      </td>

    </tr>

    <tr valign="top" class="alternate">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Latest version', 'wp-shopify'); ?>
        </label>
      </td>

      <td class="wps-col wps-col-plugin-version">

        <?php

          if (is_object($plugin_data_latest) && isset($plugin_data_latest->new_version)) {
            printf(esc_html__('%s', 'wp-shopify'), $plugin_data_latest->new_version);

          } else {
            _e('Add your license key to receive updates', 'wp-shopify');
          }

        ?>

      </td>

    </tr>

  </table>

</div>
