<!--

Related Products Show

-->
<div class="wps-admin-section">
  <div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

    <table class="form-table">

      <tbody>

        <tr valign="top">

          <th scope="row" class="titledesc">
            <?php esc_html_e( 'Show related products', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          </th>

          <td class="forminp forminp-text">
            <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_related_products_show" class="wps-label-block wps-checkbox-all">
              <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_related_products_show]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_related_products_show" type="checkbox" <?php echo $general->related_products_show ? 'checked' : ''; ?>>
            </label>
          </td>

        </tr>

      </tbody>

    </table>

  </div>
</div>
