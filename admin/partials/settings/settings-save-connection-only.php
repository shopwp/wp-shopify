<div class="wps-form-group wps-form-group-tight">

  <table class="form-table">
    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_attr_e( 'Save connection only', WPS_PLUGIN_TEXT_DOMAIN ); ?>
        </th>


        <?php if (isset($general->save_connection_only)) { ?>

          <td class="forminp forminp-text wps-checkbox-wrapper">

            <label for="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_save_connection_only" class="wps-label-block">
              <input name="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>[wps_general_save_connection_only]" id="<?= WPS_SETTINGS_GENERAL_OPTION_NAME; ?>_save_connection_only" type="checkbox" <?php echo $general->save_connection_only ? 'checked' : ''; ?> class="wps-checkbox">
            </label>

          </td>

        <?php } ?>

      </tr>

    </tbody>
  </table>

</div>
