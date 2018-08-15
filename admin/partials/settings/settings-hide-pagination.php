<!--

Load cart

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">

    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Hide all pagination', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'Checking this will hide all pagination for products and collections. Will also override any shortcode pagination settings. If you want to keep pagination but hide it for a specific shortcode, use the "pagination" shortcode attribute instead.', WPS_PLUGIN_TEXT_DOMAIN ); ?>"></span>
        </th>

        <td class="forminp forminp-text">
          <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_hide_pagination" class="wps-label-block">
            <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_hide_pagination]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_hide_pagination" type="checkbox" <?php echo $general->hide_pagination ? 'checked' : ''; ?>>
          </label>
        </td>

      </tr>
    </tbody>

  </table>

</div>
