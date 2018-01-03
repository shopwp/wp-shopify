<!--

Load cart

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">

    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Use titles for alt attributes', 'wp-shopify' ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'If you have hundreds of products you can check this to drastically speed up the syncing process', 'wp-shopify' ); ?>"></span>
        </th>

        <td class="forminp forminp-text">
          <label for="<?php echo $this->config->settings_general_option_name; ?>_title_as_alt" class="wps-label-block">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_title_as_alt]" id="<?php echo $this->config->settings_general_option_name; ?>_title_as_alt" type="checkbox" <?php echo $general->title_as_alt ? 'checked' : ''; ?>>
          </label>
        </td>

      </tr>
    </tbody>

  </table>

</div>
