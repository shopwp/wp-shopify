<!-- Products URLs -->
<div class="wps-form-group wps-form-group-tight">

  <table class="form-table">
    <tbody>
      <tr valign="top">
        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Products URL', WPS_PLUGIN_TEXT_DOMAIN ); ?>
        </th>
        <td class="forminp forminp-text wps-input-shiftleft">
          <code><?php echo get_home_url(); ?>/</code>
          <input required type="text" class="regular-text code" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_url_products" name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_url_products]" value="<?php if(!empty($general->url_products)) echo $general->url_products; ?>" placeholder="<?php esc_attr_e( 'products', WPS_PLUGIN_TEXT_DOMAIN ); ?>">
        </td>
      </tr>
    </tbody>
  </table>

</div>
