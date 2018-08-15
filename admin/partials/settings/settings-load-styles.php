<!--

Load default styles

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">
    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_attr_e( 'Load styles', WPS_PLUGIN_TEXT_DOMAIN ); ?>
        </th>

        <td class="forminp forminp-text wps-checkbox-wrapper">
          <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_styles_all" class="wps-label-block wps-checkbox-all">
            <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_styles_all]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_styles_all" type="checkbox" <?php echo $general->styles_all ? 'checked' : ''; ?>> <?php esc_html_e( 'All styles', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          </label>

          <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_styles_core" class="wps-label-block">
            <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_styles_core]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_styles_core" type="checkbox" <?php echo $general->styles_core ? 'checked' : ''; ?> <?php echo $general->styles_all ? 'disabled' : ''; ?> class="wps-checkbox"> <?php printf(__('Core styles <small>(cart, hide/show classes, etc)</small>', WPS_PLUGIN_TEXT_DOMAIN)); ?>
          </label>

          <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_styles_grid" class="wps-label-block">
            <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_styles_grid]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_styles_grid" type="checkbox" <?php echo $general->styles_grid ? 'checked' : ''; ?> <?php echo $general->styles_all ? 'disabled' : ''; ?> class="wps-checkbox"> <?php esc_html_e( 'Grid styles', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          </label>
        </td>

      </tr>

    </tbody>
  </table>

</div>
