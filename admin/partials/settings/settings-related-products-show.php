<!--

Related Products Show

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">

    <tbody>

      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Show related products', 'wp-shopify' ); ?>
        </th>

        <td class="forminp forminp-text">
          <label for="<?php echo $this->config->settings_general_option_name; ?>_related_products_show" class="wps-label-block wps-checkbox-all">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_related_products_show]" id="<?php echo $this->config->settings_general_option_name; ?>_related_products_show" type="checkbox" <?php echo $general->related_products_show ? 'checked' : ''; ?>>
          </label>
        </td>

      </tr>

    </tbody>

  </table>

</div>
