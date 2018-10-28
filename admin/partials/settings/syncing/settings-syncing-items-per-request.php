<div class="wps-form-group wps-form-group-align-top">

  <table class="form-table">
    <tbody>
      <tr valign="top">

        <th scope="row" class="titledesc">
          <?php esc_attr_e( 'Items per request', WPS_PLUGIN_TEXT_DOMAIN ); ?>
          <span class="wps-help-tip" title="<?php esc_attr_e( 'This number represents the amount of individual products that are transfered during the syncing process. You can reduce this number if you\'re running into timeout issues.', WPS_PLUGIN_TEXT_DOMAIN ); ?>"></span>
        </th>

        <td class="forminp forminp-text wps-slider-wrapper">

          <div class="wps-slider-label-wrapper wps-l-row">
            <div class="wps-slider-amount" id="wps-items-per-request-amount"><?= $general->items_per_request; ?></div>
          </div>

          <div class="slider wps-slider-items-per-request"></div>

        </td>

      </tr>

    </tbody>
  </table>

</div>
