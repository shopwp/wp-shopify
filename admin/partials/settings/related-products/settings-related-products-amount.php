<!--

Related Products Amount

-->
<div class="wps-admin-section">
  <div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

    <table class="form-table">

      <tbody>

        <tr valign="top">

          <th scope="row" class="titledesc">
            <?php esc_html_e( 'Related products to show', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          </th>

          <td class="forminp forminp-text">
            <input type="number" class="small-text" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_related_products_amount" name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_related_products_amount]" value="<?php echo !empty($general->related_products_amount) ? $general->related_products_amount : 4; ?>" placeholder="">
          </td>

        </tr>

      </tbody>

    </table>

  </div>
</div>
