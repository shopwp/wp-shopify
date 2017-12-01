<!-- Collections URLs -->
<div class="wps-form-group wps-form-group-tight">

  <table class="form-table">
    <tbody>
      <tr valign="top">
        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Collections URL', 'wp-shopify' ); ?>
        </th>
        <td class="forminp forminp-text">
          <code><?php echo get_home_url(); ?>/</code>
          <input required type="text" class="regular-text code" id="<?php echo $this->config->settings_general_option_name; ?>_url_collections" name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_url_collections]" value="<?php if(!empty($general->url_collections)) echo $general->url_collections; ?>" placeholder="<?php esc_attr_e( 'collections', 'wp-shopify' ); ?>">
        </td>
      </tr>
    </tbody>
  </table>

</div>
