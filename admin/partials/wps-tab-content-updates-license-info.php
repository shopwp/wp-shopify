<!--

License Info

-->

<?php

if (is_object($license)) {

  $expires = $license->expires ? $license->expires : false;
  $licenseLimit = $license->license_limit ? $license->license_limit : 'unlimited';
  $custName = $license->customer_name ? $license->customer_name : false;
  $custEmail = $license->customer_email ? $license->customer_email : false;

} else {

  $expires = false;
  $licenseLimit = false;
  $custName = false;
  $custEmail = false;

}

?>

<div class="postbox wps-postbox-license-info <?php echo $activeLicense ? '' : 'wps-is-hidden'; ?>">
  <table class="form-table">

    <tr>

      <th class="row-title">
        <?php esc_html_e('License Key Information', 'wp-shopify'); ?>
      </th>

      <th>
        <?php esc_html_e('', 'wp-shopify' ); ?>
      </th>

    </tr>

    <tr valign="top">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Status', 'wp-shopify'); ?>
        </label>
      </td>

      <td class="wps-col wps-col-license-status wps-col-license-status-<?php echo strtolower($status); ?>">
        <?php printf(esc_html__('%s', 'wp-shopify'), $status); ?>
      </td>

    </tr>

    <tr valign="top">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Name', 'wp-shopify'); ?>
        </label>
      </td>

      <td class="wps-col wps-col-license-name">
        <?php printf(esc_html__('%s', 'wp-shopify'), $custName); ?>
      </td>

    </tr>

    <tr valign="top" class="alternate">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Email', 'wp-shopify'); ?>
        </label>
      </td>

      <td class="wps-col wps-col-license-email">
        <?php printf(esc_html__('%s', 'wp-shopify'), $custEmail); ?>
      </td>

    </tr>

    <tr valign="top">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Expires on', 'wp-shopify'); ?>
        </label>
      </td>

      <td class="wps-col wps-col-license-expire">

        <?php

        if ($expires === '1970-01-01 00:00:00' || $expires === 0 || $expires === false) {
          esc_html_e('Never expires', 'wp-shopify');

        } else {

          echo date_i18n("F j, Y", strtotime($expires));

        }

        ?>

      </td>

    </tr>

    <tr valign="top">

      <td scope="row">
        <label for="tablecell">
          <?php esc_html_e('Activation count', 'wp-shopify'); ?>
        </label>
      </td>

      <td class="wps-col wps-col-license-limit">

        <?php printf(esc_html__('%1$d / %2$d', 'wp-shopify'), $count, $licenseLimit); ?>

        <small class="wps-table-supporting">
          <?php esc_html_e('(Activations on dev environents don\'t add to total)', 'wp-shopify'); ?>
        </small>

      </td>

    </tr>

  </table>

</div>
