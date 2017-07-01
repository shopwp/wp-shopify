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
      <th class="row-title"><?php esc_attr_e( 'License Key Information', 'wp_admin_style' ); ?></th>
      <th><?php esc_attr_e( '', 'wp_admin_style' ); ?></th>
    </tr>
    <tr valign="top">
      <td scope="row">
        <label for="tablecell"><?php esc_attr_e('Status', 'wp_admin_style'); ?></label>
      </td>
      <td class="wps-col wps-col-license-status wps-col-license-status-<?php echo strtolower($status); ?>">
        <?php esc_attr_e($status, 'wp_admin_style' ); ?>
      </td>
    </tr>
    <tr valign="top">
      <td scope="row">
        <label for="tablecell"><?php esc_attr_e('Name', 'wp_admin_style'); ?></label>
      </td>
      <td class="wps-col wps-col-license-name">
        <?php esc_attr_e($custName, 'wp_admin_style' ); ?>
      </td>
    </tr>
    <tr valign="top" class="alternate">
      <td scope="row">
        <label for="tablecell"><?php esc_attr_e('Email', 'wp_admin_style'); ?> </label>
      </td>
      <td class="wps-col wps-col-license-email">
        <?php esc_attr_e($custEmail, 'wp_admin_style' ); ?>
      </td>
    </tr>
    <tr valign="top">
      <td scope="row">
        <label for="tablecell"><?php esc_attr_e('Expires on', 'wp_admin_style'); ?></label>
      </td>
      <td class="wps-col wps-col-license-expire">

        <?php

        if ($expires === '1970-01-01 00:00:00') {
          echo 'Never expires';

        } else {
          esc_attr_e(date("F j, Y", strtotime($expires)), 'wp_admin_style' );
        }

        ?>

      </td>

    </tr>
    <tr valign="top">
      <td scope="row">
        <label for="tablecell"><?php esc_attr_e('Activation count', 'wp_admin_style'); ?></label>
      </td>
      <td class="wps-col wps-col-license-limit"><?php esc_attr_e($count . ' / ' . $licenseLimit, 'wp_admin_style' ); ?> <small class="wps-table-supporting">(Activations on dev environents don't add to total)</small></td>
    </tr>
  </table>

</div>
