<!--

Load cart

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">

    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_html_e( 'Show breadcrumbs', 'wp-shopify' ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'Show breadcrumbs', 'wp-shopify' ); ?>"></span>
        </th>

        <td class="forminp forminp-text">
          <label for="<?php echo $this->config->settings_general_option_name; ?>_show_breadcrumbs" class="wps-label-block">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_show_breadcrumbs]" id="<?php echo $this->config->settings_general_option_name; ?>_show_breadcrumbs" type="checkbox" <?php echo $general->show_breadcrumbs ? 'checked' : ''; ?>>
          </label>
        </td>

      </tr>
    </tbody>

  </table>

</div>
