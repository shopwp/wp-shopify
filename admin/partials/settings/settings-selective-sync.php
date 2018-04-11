<!--

Load default styles

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">
    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_attr_e( 'Selective sync', 'wp-shopify' ); ?>
        </th>

        <td class="forminp forminp-text wps-checkbox-wrapper">

          <label for="<?php echo $this->config->settings_general_option_name; ?>_selective_sync_all" class="wps-label-block wps-checkbox-all">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_selective_sync_all]" id="<?php echo $this->config->settings_general_option_name; ?>_selective_sync_all" type="checkbox" <?php echo $general->selective_sync_all ? 'checked' : ''; ?>> <?php esc_html_e( 'All data', 'wp-shopify' ); ?>
          </label>

          <label for="<?php echo $this->config->settings_general_option_name; ?>_selective_sync_products" class="wps-label-block">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_selective_sync_products]" id="<?php echo $this->config->settings_general_option_name; ?>_selective_sync_products" type="checkbox" <?php echo $general->selective_sync_products ? 'checked' : ''; ?> <?php echo $general->selective_sync_all ? 'disabled' : ''; ?> class="wps-checkbox"> <?php printf(__( 'Products <small>(includes images, tags, variant data, etc)</small>', 'wp-shopify' )); ?>
          </label>

          <label for="<?php echo $this->config->settings_general_option_name; ?>_selective_sync_collections" class="wps-label-block">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_selective_sync_collections]" id="<?php echo $this->config->settings_general_option_name; ?>_selective_sync_collections" type="checkbox" <?php echo $general->selective_sync_collections ? 'checked' : ''; ?> <?php echo $general->selective_sync_all ? 'disabled' : ''; ?> class="wps-checkbox"> <?php esc_html_e( 'Collections', 'wp-shopify' ); ?>
          </label>

          <label for="<?php echo $this->config->settings_general_option_name; ?>_selective_sync_customers" class="wps-label-block">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_selective_sync_customers]" id="<?php echo $this->config->settings_general_option_name; ?>_selective_sync_customers" type="checkbox" <?php echo $general->selective_sync_customers ? 'checked' : ''; ?> <?php echo $general->selective_sync_all ? 'disabled' : ''; ?> class="wps-checkbox"> <?php esc_html_e( 'Customers', 'wp-shopify' ); ?>
          </label>

          <label for="<?php echo $this->config->settings_general_option_name; ?>_selective_sync_orders" class="wps-label-block">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_selective_sync_orders]" id="<?php echo $this->config->settings_general_option_name; ?>_selective_sync_orders" type="checkbox" <?php echo $general->selective_sync_orders ? 'checked' : ''; ?> <?php echo $general->selective_sync_all ? 'disabled' : ''; ?> class="wps-checkbox"> <?php esc_html_e( 'Orders', 'wp-shopify' ); ?>
          </label>

          <!-- <label for="<?php echo $this->config->settings_general_option_name; ?>_selective_sync_shop" class="wps-label-block">
            <input name="<?php echo $this->config->settings_general_option_name; ?>[wps_general_selective_sync_shop]" id="<?php echo $this->config->settings_general_option_name; ?>_selective_sync_shop" type="checkbox" <?php echo $general->selective_sync_shop ? 'checked' : ''; ?> <?php echo $general->selective_sync_all ? 'disabled' : ''; ?> class="wps-checkbox"> <?php printf(__('Shop <small>(Includes store name, location, phone number, etc. Warning: Choosing not to sync shop data will default money format to US Dollars.)</small>', 'wp-shopify')); ?>
          </label> -->

        </td>

      </tr>

    </tbody>
  </table>

</div>
