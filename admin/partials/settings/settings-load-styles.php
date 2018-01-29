<!--

Load default styles

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">
    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_attr_e( 'Load styles', 'wp-shopify' ); ?>
        </th>

        <td class="forminp forminp-text wps-checkbox-wrapper">
          <label for="<?php echo $this->config->settings_general_option_name; ?>_styles_all" class="wps-label-block wps-checkbox-all">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_styles_all]" id="<?php echo $this->config->settings_general_option_name; ?>_styles_all" type="checkbox" <?php echo $general->styles_all ? 'checked' : ''; ?>> <?php esc_html_e( 'All styles', 'wp-shopify' ); ?>
          </label>

          <label for="<?php echo $this->config->settings_general_option_name; ?>_styles_core" class="wps-label-block">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_styles_core]" id="<?php echo $this->config->settings_general_option_name; ?>_styles_core" type="checkbox" <?php echo $general->styles_core ? 'checked' : ''; ?> <?php echo $general->styles_all ? 'disabled' : ''; ?> class="wps-checkbox"> <?php printf(__('Core styles <small>(cart, hide/show classes, etc)</small>', 'wp-shopify')); ?>
          </label>

          <label for="<?php echo $this->config->settings_general_option_name; ?>_styles_grid" class="wps-label-block">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_styles_grid]" id="<?php echo $this->config->settings_general_option_name; ?>_styles_grid" type="checkbox" <?php echo $general->styles_grid ? 'checked' : ''; ?> <?php echo $general->styles_all ? 'disabled' : ''; ?> class="wps-checkbox"> <?php esc_html_e( 'Grid styles', 'wp-shopify' ); ?>
          </label>
        </td>

      </tr>

    </tbody>
  </table>

</div>
