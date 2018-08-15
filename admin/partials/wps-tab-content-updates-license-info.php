<!--

License Info

-->
<div class="postbox wps-postbox-license-info <?php echo $activeLicense ? '' : 'wps-is-hidden'; ?>">
  <table class="form-table">

    <tr valign="top">

      <th class="row-title">
        <?php esc_html_e('License Key Information', WPS_PLUGIN_TEXT_DOMAIN); ?>
      </th>

      <th></th>

    </tr>

    <tr valign="top" class="alternate">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Status', WPS_PLUGIN_TEXT_DOMAIN); ?>
        </label>
      </td>

      <td class="wps-col wps-col-license-status wps-col-license-status-<?php echo strtolower($status); ?>">
        <?php printf(esc_html__('%s', WPS_PLUGIN_TEXT_DOMAIN), $status); ?>
      </td>

    </tr>

    <tr valign="top">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Name', WPS_PLUGIN_TEXT_DOMAIN); ?>
        </label>
      </td>

      <td class="wps-col wps-col-license-name">
        <?php printf(esc_html__('%s', WPS_PLUGIN_TEXT_DOMAIN), $custName); ?>
      </td>

    </tr>

    <tr valign="top" class="alternate">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Email', WPS_PLUGIN_TEXT_DOMAIN); ?>
        </label>
      </td>

      <td class="wps-col wps-col-license-email">
        <?php printf(esc_html__('%s', WPS_PLUGIN_TEXT_DOMAIN), $custEmail); ?>
      </td>

    </tr>

    <tr valign="top">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Expires on', WPS_PLUGIN_TEXT_DOMAIN); ?>
        </label>
      </td>

      <td class="wps-col wps-col-license-expire">

        <?php

        if (strpos($expires, '1970-01-01') !== false || $expires === 0 || $expires === false) {
          esc_html_e('Never expires', WPS_PLUGIN_TEXT_DOMAIN);

        } else {
          echo date_i18n("F j, Y", strtotime($expires));

        }

        ?>

      </td>

    </tr>

    <tr valign="top" class="alternate">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Activation count', WPS_PLUGIN_TEXT_DOMAIN); ?>
        </label>
      </td>

      <td class="wps-col wps-col-license-limit">

        <?php printf(esc_html__('%1$d / %2$d', WPS_PLUGIN_TEXT_DOMAIN), $count, $licenseLimit); ?>

        <?php if (isset($license) && $license->is_local) { ?>
          <small class="wps-table-supporting">
            <?php esc_html_e('(Activations on dev environments don\'t add to total)', WPS_PLUGIN_TEXT_DOMAIN); ?>
          </small>
        <?php } ?>

      </td>

    </tr>

  </table>

</div>
