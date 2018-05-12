<!--

Load cart

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">

    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Products link to Shopify', 'wp-shopify' ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'When this is enabled, products will not link to WP Shopify product single pages. Instead they\'ll link back to the product detail page on Shopify. Note: this setting is irrelevant if using Shopify Lite plan. Important: toggling this on/off will require you to clear the WP Shopify cache aftwards. You can do this from within Tools - Clear Transients', 'wp-shopify' ); ?>"></span>
        </th>

        <td class="forminp forminp-text">
          <label for="<?php echo $this->config->settings_general_option_name; ?>_products_link_to_shopify" class="wps-label-block">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_products_link_to_shopify]" id="<?php echo $this->config->settings_general_option_name; ?>_products_link_to_shopify" type="checkbox" <?php echo $general->products_link_to_shopify ? 'checked' : ''; ?>>
          </label>
        </td>

      </tr>
    </tbody>

  </table>

</div>
