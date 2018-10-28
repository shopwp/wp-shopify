<!--

Load cart

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">

    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Cart terms', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'When enabled, generates a mandatory checkbox within the cart that must be checked before checking out.', WPS_PLUGIN_TEXT_DOMAIN ); ?>"></span>
        </th>

        <td class="forminp forminp-text">
          <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_enable_cart_terms]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_enable_cart_terms" type="checkbox" <?php echo $general->enable_cart_terms ? 'checked' : ''; ?>>
        </td>

      </tr>
    </tbody>

  </table>

</div>
