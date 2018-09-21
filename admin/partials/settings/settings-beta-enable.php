<!--

Enable Beta version

-->

<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">
    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_attr_e( 'Enable Beta Updates', WPS_PLUGIN_TEXT_DOMAIN ); ?>
        </th>

        <td class="forminp forminp-text wps-checkbox-wrapper">

          <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_enable_beta" class="wps-label-block wps-checkbox-all">
            <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_enable_beta]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_enable_beta" type="checkbox" <?= $general->enable_beta ? 'checked' : ''; ?>> <?php esc_html_e( 'Enable', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          </label>

        </td>

      </tr>

    </tbody>
  </table>

</div>
