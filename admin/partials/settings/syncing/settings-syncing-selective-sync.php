<!--

Load default styles

-->
<div class="wps-form-group wps-form-group-tight wps-form-group-align-top">

  <table class="form-table">
    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_attr_e( 'Sync by data type', WPS_PLUGIN_TEXT_DOMAIN ); ?>
        </th>

        <td class="forminp forminp-text wps-checkbox-wrapper">

          <div class="wps-label-block-wrapper wps-checkbox-all">

            <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_selective_sync_all]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_selective_sync_all" type="checkbox" <?php echo $general->selective_sync_all ? 'checked' : ''; ?>>

            <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_selective_sync_all" class="wps-label-block wps-checkbox-all">
              <?php esc_html_e( 'All data', WPS_PLUGIN_TEXT_DOMAIN ); ?>
            </label>

          </div>


          <div class="wps-label-block-wrapper">
            <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_selective_sync_products]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_selective_sync_products" type="checkbox" <?php echo $general->selective_sync_products ? 'checked' : ''; ?> <?php echo $general->selective_sync_all ? 'disabled' : ''; ?> class="wps-checkbox">

            <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_selective_sync_products" class="wps-label-block">
               <?php printf(__( 'Products <small>(includes images, tags, variant data, etc)</small>', WPS_PLUGIN_TEXT_DOMAIN )); ?>
            </label>

          </div>


          <div class="wps-label-block-wrapper">

            <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_selective_sync_collections]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_selective_sync_collections" type="checkbox" <?php echo $general->selective_sync_collections ? 'checked' : ''; ?> <?php echo $general->selective_sync_all ? 'disabled' : ''; ?> class="wps-checkbox">

            <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_selective_sync_collections" class="wps-label-block">
              <?php esc_html_e( 'Collections', WPS_PLUGIN_TEXT_DOMAIN ); ?>
            </label>

          </div>


          <div class="wps-label-block-wrapper">

            <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_selective_sync_customers]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_selective_sync_customers" type="checkbox" <?php echo $general->selective_sync_customers ? 'checked' : ''; ?> <?php echo $general->selective_sync_all ? 'disabled' : ''; ?> class="wps-checkbox">

            <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_selective_sync_customers" class="wps-label-block">
              <?php esc_html_e( 'Customers', WPS_PLUGIN_TEXT_DOMAIN ); ?>
            </label>

          </div>



          <div class="wps-label-block-wrapper">

            <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_selective_sync_orders]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_selective_sync_orders" type="checkbox" <?php echo $general->selective_sync_orders ? 'checked' : ''; ?> <?php echo $general->selective_sync_all ? 'disabled' : ''; ?> class="wps-checkbox">

            <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_selective_sync_orders" class="wps-label-block">
              <?php esc_html_e( 'Orders', WPS_PLUGIN_TEXT_DOMAIN ); ?>
            </label>

          </div>

        </td>

      </tr>

    </tbody>
  </table>

</div>
