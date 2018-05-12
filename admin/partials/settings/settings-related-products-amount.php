<!--

Related Products Amount

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">

    <tbody>

      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Related products to show', 'wp-shopify' ); ?>
        </th>

        <td class="forminp forminp-text">
          <input type="number" class="small-text" id="<?php echo $this->config->settings_general_option_name; ?>_related_products_amount" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_related_products_amount]" value="<?php echo !empty($general->related_products_amount) ? $general->related_products_amount : 4; ?>" placeholder="">
        </td>

      </tr>

    </tbody>

  </table>

</div>
