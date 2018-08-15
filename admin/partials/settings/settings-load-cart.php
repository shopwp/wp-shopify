<!--

Load cart

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">

    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Load cart', WPS_PLUGIN_TEXT_DOMAIN ); ?>
        </th>

        <td class="forminp forminp-text">
          <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_cart_loaded" class="wps-label-block">
            <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_cart_loaded]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_cart_loaded" type="checkbox" <?php echo $general->cart_loaded ? 'checked' : ''; ?>>
          </label>
        </td>

      </tr>
    </tbody>

  </table>

</div>
