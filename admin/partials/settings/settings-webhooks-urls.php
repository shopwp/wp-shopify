<!-- Webook URL -->
<div class="wps-form-group wps-form-group-tight">

  <table class="form-table">
    <tbody>
      <tr valign="top">
        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Webhooks callback URL', 'wp-shopify' ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'This will be the location where Shopify sends its updates. For example, after updating a Product title within Shopify, the newly changed title will be sent here. You\'ll most likely want this to be the same domain as the WordPress site. For developers, this needs to be publicly accesible. Change to a proxy URL during local development using something like ngrok', 'wp-shopify' ); ?>"></span>
        </th>
        <td class="forminp forminp-text">
          <input required type="text" class="regular-text" id="<?php echo $this->config->settings_general_option_name; ?>_url_webhooks" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_webhooks_products]" value="<?php if(!empty($general->url_webhooks)) echo $general->url_webhooks; ?>" placeholder="<?php esc_attr_e(get_home_url(), 'wp-shopify' ); ?>">
        </td>
      </tr>
    </tbody>
  </table>

</div>
