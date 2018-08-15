<!-- Collections URLs -->
<div class="wps-form-group wps-form-group-tight">

  <table class="form-table">
    <tbody>
      <tr valign="top">
        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Collections URL', WPS_PLUGIN_TEXT_DOMAIN ); ?>
        </th>
        <td class="forminp forminp-text wps-input-shiftleft">
          <code><?= get_home_url(); ?>/</code>
          <input required type="text" class="regular-text code" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_url_collections" name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_url_collections]" value="<?php if(!empty($general->url_collections)) echo $general->url_collections; ?>" placeholder="<?php esc_attr_e( 'collections', WPS_PLUGIN_TEXT_DOMAIN ); ?>">
        </td>
      </tr>
    </tbody>
  </table>

</div>
