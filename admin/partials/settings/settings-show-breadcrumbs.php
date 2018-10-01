<!--

Load cart

-->
<div class="wps-form-group wps-form-group-align-top">

  <table class="form-table">

    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Show breadcrumbs', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'Shows breadcrumbs', WPS_PLUGIN_TEXT_DOMAIN ); ?>"></span>
        </th>

        <td class="forminp forminp-text">
          <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_show_breadcrumbs]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_show_breadcrumbs" type="checkbox" <?php echo $general->show_breadcrumbs ? 'checked' : ''; ?>>
        </td>

      </tr>
    </tbody>

  </table>

</div>
