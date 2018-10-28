<?php

/*

This file is used to markup the admin-facing aspects of WP Shopify

*/
use WPS\Factories\DB_Settings_Connection_Factory;
use WPS\Factories\DB_Settings_License_Factory;
use WPS\Factories\DB_Settings_General_Factory;


use WPS\Utils;

$DB_Settings_Connection = DB_Settings_Connection_Factory::build();
$DB_Settings_License = DB_Settings_License_Factory::build();
$DB_Settings_General = DB_Settings_General_Factory::build();

$connection = $DB_Settings_Connection->get();
$license = $DB_Settings_License->get();
$general = $DB_Settings_General->get();

$plugin_new_version = WPS_NEW_PLUGIN_VERSION;


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


if (!empty($connection)) {

  if ($connection->api_key) {
    $connected = true;

  } else {
    $connected = false;

  }

} else {
  $connected = false;
}


if (!empty($license)) {

  if (isset($license->key)) {
    $activeLicense = true;
    $maskedKey = Utils::wps_mask_value($license->key);

  } else if (isset($license->license_key)) {
    $activeLicense = true;
    $maskedKey = Utils::wps_mask_value($license->license_key);

  } else {
    $activeLicense = false;
  }


  if ($license->is_local === 1) {
    $count = $license->site_count - 1;

  } else {
    $count = $license->site_count;

  }


  if ($license->success) {
    $status = 'Active';

  } else {
    $status = 'Inactive';

  }

} else {
  $activeLicense = false;
  $count = false;
  $status = 'Inactive';

}

$tab = null;

?>

<div class="wrap wps-admin-wrap">

  <h2>

    <?php esc_attr_e('WP Shopify', WPS_PLUGIN_TEXT_DOMAIN); ?>

    <sup class="wps-version-pill wps-version-pill-sm">
      <?php echo $plugin_new_version; ?>
    </sup>

  </h2>

  <?php

  require_once plugin_dir_path( __FILE__ ) . 'wps-tabs.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-admin-notices.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-connect.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-settings.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-tools.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-license.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-help.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-misc.php';

  ?>

</div>
