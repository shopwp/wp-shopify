<div class="postbox wps-postbox-plugin-info" id="wps-plugin-info">

  <div class="spinner"></div>

  <h3><?php esc_html_e('Plugin Information', WPS_PLUGIN_TEXT_DOMAIN); ?></h3>

  <table class="form-table wps-is-hidden">

    <tr valign="top">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Name', WPS_PLUGIN_TEXT_DOMAIN); ?>
        </label>
      </td>

      <td class="wps-col wps-col-plugin-name">
        <?php esc_attr_e(WPS_PLUGIN_NAME_FULL_PRO, WPS_PLUGIN_TEXT_DOMAIN); ?>
      </td>

    </tr>

    <tr valign="top" class="alternate">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Tested up to WordPress', WPS_PLUGIN_TEXT_DOMAIN); ?>
        </label>
      </td>

      <td class="wps-col wps-col-tested-up-to">
        <?php echo get_bloginfo('version'); ?>
      </td>

    </tr>

    <tr valign="top">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Installed version', WPS_PLUGIN_TEXT_DOMAIN); ?>
        </label>
      </td>

      <td class="wps-col wps-col-plugin-ver">
        <?php esc_html_e(WPS_NEW_PLUGIN_VERSION, WPS_PLUGIN_TEXT_DOMAIN ); ?>
      </td>

    </tr>

    <tr valign="top" class="alternate">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Latest version', WPS_PLUGIN_TEXT_DOMAIN); ?>
        </label>
      </td>

      <td class="wps-col wps-col-plugin-version">
        <?= WPS_NEW_PLUGIN_VERSION; ?>
      </td>

    </tr>

  </table>

</div>
