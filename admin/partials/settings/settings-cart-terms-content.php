<!--

Load cart

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top" id="wrapper-cart-terms-content">

  <table class="form-table">

    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Cart terms text', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'This is the text that will show next to the terms checkbox. HTML tags <a>, <b>, <i>, <em>, and <strong> are allowed.', WPS_PLUGIN_TEXT_DOMAIN ); ?>"></span>
        </th>

        <td class="forminp forminp-text">
          <textarea rows="5" cols="60" name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_cart_terms_content]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_cart_terms_content" class="" placeholder=""><?= esc_textarea($general->cart_terms_content); ?></textarea>
        </td>

      </tr>
    </tbody>

  </table>

</div>
