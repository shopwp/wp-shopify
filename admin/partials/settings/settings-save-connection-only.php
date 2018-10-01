<div class="wps-form-group wps-form-group-align-top">

  <table class="form-table">

    <tbody>

      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_attr_e( 'Save connection only', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'When enabled, only the connection to Shopify will be saved during the syncing process. Useful if you want to skip syncing webhooks, or if you want to sync by collections without having to sync everything first.', WPS_PLUGIN_TEXT_DOMAIN ); ?>"></span>
        </th>

        <?php if (isset($general->save_connection_only)) { ?>

          <td class="forminp forminp-text wps-checkbox-wrapper">
            <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_save_connection_only]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_save_connection_only" type="checkbox" <?php echo $general->save_connection_only ? 'checked' : ''; ?> class="wps-checkbox">
          </td>

        <?php } ?>

      </tr>

    </tbody>

  </table>

</div>
