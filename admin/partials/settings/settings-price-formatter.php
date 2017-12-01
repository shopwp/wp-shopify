<!--

Price Formatter

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">
    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_attr_e( 'Price formatting', 'wp-shopify' ); ?>
        </th>

        <td class="forminp forminp-text">
          <label for="<?php echo $this->config->settings_general_option_name; ?>_price_with_currency" class="wps-label-block">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_price_with_currency]" id="<?php echo $this->config->settings_general_option_name; ?>_price_with_currency" type="checkbox" <?php echo $general->price_with_currency ? 'checked' : ''; ?>> <?php esc_attr_e('Show currency symbol with price', 'wp-shopify' ); ?>
          </label>
        </td>

      </tr>

    </tbody>
  </table>

</div>
