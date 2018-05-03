<!--

Load cart

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">

    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Hide pagination', 'wp-shopify' ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'Hide pagination', 'wp-shopify' ); ?>"></span>
        </th>

        <td class="forminp forminp-text">
          <label for="<?php echo $this->config->settings_general_option_name; ?>_hide_pagination" class="wps-label-block">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_hide_pagination]" id="<?php echo $this->config->settings_general_option_name; ?>_hide_pagination" type="checkbox" <?php echo $general->hide_pagination ? 'checked' : ''; ?>>
          </label>
        </td>

      </tr>
    </tbody>

  </table>

</div>
