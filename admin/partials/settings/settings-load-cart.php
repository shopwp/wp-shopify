<!--

Load cart

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">

    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Load cart', 'wp-shopify' ); ?>
        </th>

        <td class="forminp forminp-text">
          <label for="<?php echo $this->config->settings_general_option_name; ?>_cart_loaded" class="wps-label-block">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_cart_loaded]" id="<?php echo $this->config->settings_general_option_name; ?>_cart_loaded" type="checkbox" <?php echo $general->cart_loaded ? 'checked' : ''; ?>>
          </label>
        </td>

      </tr>
    </tbody>

  </table>

</div>
