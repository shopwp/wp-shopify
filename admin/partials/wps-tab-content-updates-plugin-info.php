<!--

Plugin Info

-->

<div class="postbox wps-postbox-plugin-info">
  <table class="form-table">
    <tr>
      <th class="row-title"><?php esc_attr_e( 'Plugin Information', 'wp_admin_style' ); ?></th>
      <th><?php esc_attr_e( '', 'wp_admin_style' ); ?></th>
    </tr>
    <tr valign="top">
      <td scope="row">
        <label for="tablecell"><?php esc_attr_e('Current version', 'wp_admin_style'); ?></label>
      </td>
      <td class="wps-col wps-col-plugin-ver"><?php esc_attr_e( $plugin_current_version, 'wp_admin_style' ); ?></td>
    </tr>
    <tr valign="top" class="alternate">
      <td scope="row">
        <label for="tablecell"><?php esc_attr_e('Latest version', 'wp_admin_style'); ?> </label>
      </td>
      <td class="wps-col wps-col-plugin-version">
        <?php

          if (is_object($plugin_data_latest)) {
            esc_attr_e( $plugin_data_latest->new_version, 'wp_admin_style' );

          } else {
            echo $plugin_data_latest;
          }

        ?>
      </td>
    </tr>
    <tr valign="top">
      <td scope="row">
        <label for="tablecell"><?php esc_attr_e('Update Available', 'wp_admin_style'); ?></label>
      </td>

      <?php


      if (is_object($plugin_data_latest)) {

        if ($plugin_data_latest->new_version === $plugin_current_version) { ?>

          <td class="wps-col wps-col-plugin-update-avail"><?php esc_attr_e( 'No', 'wp_admin_style' ); ?></td>

        <?php } else { ?>

          <td class="wps-col wps-col-plugin-update-avail">

            <span class="wps-col-license-status-notify"><?php esc_attr_e( 'Yes', 'wp_admin_style' ); ?></span>

            <?php if ($License->has_valid_key()) { ?>
              <p class="wps-table-supporting"><a href="<?php echo get_admin_url(); ?>plugins.php">Update now</a></p>

            <?php } else { ?>
              <p class="wps-table-supporting">(Please enter a valid license key to receive updates)</p>

            <?php } ?>

          </td>

        <?php } ?>

      <?php } ?>


    </tr>
  </table>

</div>
