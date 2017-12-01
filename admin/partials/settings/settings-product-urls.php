<!-- Products URLs -->
<div class="wps-form-group wps-form-group-tight">

  <table class="form-table">
    <tbody>
      <tr valign="top">
        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Products URL', 'wp-shopify' ); ?>
        </th>
        <td class="forminp forminp-text">
          <code><?php echo get_home_url(); ?>/</code>
          <input required type="text" class="regular-text code" id="<?php echo $this->config->settings_general_option_name; ?>_url_products" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_url_products]" value="<?php if(!empty($general->url_products)) echo $general->url_products; ?>" placeholder="<?php esc_attr_e( 'products', 'wp-shopify' ); ?>">
        </td>
      </tr>
    </tbody>
  </table>

</div>
