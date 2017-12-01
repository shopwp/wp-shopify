<!--

Plugin Info

-->

<div class="postbox wps-postbox-plugin-info">

  <table class="form-table">

    <tr>
      <th class="row-title">
        <?php esc_html_e('Plugin Information', 'wp-shopify'); ?>
      </th>

      <th>
        <?php esc_html_e('', 'wp-shopify' ); ?>
      </th>
    </tr>

    <tr valign="top">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Current version', 'wp-shopify'); ?>
        </label>
      </td>

      <td class="wps-col wps-col-plugin-ver">
        <?php esc_html_e($plugin_current_version, 'wp-shopify' ); ?>
      </td>

    </tr>

    <tr valign="top" class="alternate">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Latest version', 'wp-shopify'); ?>
        </label>
      </td>

      <td class="wps-col wps-col-plugin-version">
        <?php

          if (is_object($plugin_data_latest)) {
            printf(esc_html__('%s', 'wp-shopify'), $plugin_data_latest->new_version);

          } else {
            printf(esc_html__('%s', 'wp-shopify'), $plugin_data_latest);

          }

        ?>
      </td>

    </tr>

    <tr valign="top">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Update Available', 'wp-shopify'); ?>
        </label>
      </td>

      <?php

      if (is_object($plugin_data_latest)) {

        if ($plugin_data_latest->new_version === $plugin_current_version) { ?>

          <td class="wps-col wps-col-plugin-update-avail">
            <?php esc_html_e( 'No', 'wp-shopify' ); ?>
          </td>

        <?php } else { ?>

          <td class="wps-col wps-col-plugin-update-avail">

            <span class="wps-col-license-status-notify">
              <?php esc_html_e( 'Yes', 'wp-shopify' ); ?>
            </span>

            <?php if ($License->has_valid_key()) { ?>
              <p class="wps-table-supporting">

                <a href="<?php echo esc_url(get_admin_url()); ?>plugins.php">
                  <?php esc_html_e( 'Update now', 'wp-shopify' ); ?>
                </a>
              </p>

            <?php } else { ?>

              <p class="wps-table-supporting">
                <?php esc_html_e('(Please enter a valid license key to receive updates)', 'wp-shopify'); ?>
              </p>

            <?php } ?>

          </td>

        <?php } ?>

      <?php } ?>

    </tr>

  </table>

</div>
