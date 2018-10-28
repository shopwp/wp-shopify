<div class="wps-form-group wps-form-group-tight">

  <table class="form-table">
    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Webhooks callback URL', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'This will be the location where Shopify sends its updates. For example, after changing the Product title within Shopify, data containing the new title will be sent here. You\'ll most likely want this to be the same domain as the WordPress site. This needs to be publicly accesible. Change to a proxy URL during local development using something like ngrok. Also if you have WordPress installed in a subdirectory be sure to include that in the url.', WPS_PLUGIN_TEXT_DOMAIN ); ?>"></span>
        </th>

        <td class="forminp forminp-text">
          <input required type="text" class="regular-text" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_url_webhooks" name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_webhooks_products]" value="<?php if(!empty($general->url_webhooks)) echo $general->url_webhooks; ?>" placeholder="<?php esc_attr_e(get_home_url(), WPS_PLUGIN_TEXT_DOMAIN ); ?>">
        </td>
        
      </tr>
    </tbody>
  </table>

</div>
