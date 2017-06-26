<?php

use WPS\Utils;
use WPS\Config;
use WPS\License;
use WPS\DB\Settings_Connection;

/*

This file is used to markup the admin-facing aspects of WP Shopify

@link       https://blog.simpleblend.net
@since      1.0.0

@package    WPS
@subpackage WPS/admin/partials

*/

$connection = $this->config->wps_get_settings_connection();
$license = $this->config->wps_get_settings_license();
$Config = new Config();
$License = new License($Config);
$plugin_current_version = $Config->plugin_version;
$plugin_data_latest = $License->wps_get_latest_plugin_version();


if(!empty($connection)) {

  if ($connection->access_token) {
    $connected = true;

  } else {
    $connected = false;

  }

} else {
  $connected = false;
}


if (!empty($license)) {

  if ($license->key) {
    $activeLicense = true;
    $maskedKey = Utils::wps_mask_value($license->key);

  } else {
    $activeLicense = false;
  }

} else {
  $activeLicense = false;

}


if (!empty($license)) {

  if ($license->is_local === 1) {
    $count = $license->site_count - 1;

  } else {
    $count = $license->site_count;

  }

} else {
  $count = false;

}


if (!empty($license)) {

  if ($license->success) {
    $status = 'Active';

  } else {
    $status = 'Inactive';

  }

} else {
  $status = 'Inactive';

}


$tab = null;


?>


<div class="wrap wps-admin-wrap">

  <h2>WP Shopify <?php echo esc_html(get_admin_page_title()); ?></h2>

  <?php

  require_once plugin_dir_path( __FILE__ ) . 'wps-tabs.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-admin-notices.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-connect.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-settings.php';
  // require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-tools.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-updates.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-help.php';

  ?>

</div>

<!-- <progress max="100" value="10" class="wps-loader">
  <div class="wps-loader-body">
    <span style="width: 10%;">Progress: 10%</span>
  </div>
</progress> -->

<?php // echo do_action('wps_after_settings_form'); ?>
