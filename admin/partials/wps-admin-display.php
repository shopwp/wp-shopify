<?php

/*

This file is used to markup the admin-facing aspects of WP Shopify

*/

use WPS\Utils;
use WPS\Config;
use WPS\License;
use WPS\DB\Settings_Connection;

$connection = $this->config->wps_get_settings_connection();
$license = $this->config->wps_get_settings_license();
$Config = new Config();
$License = new License($Config);
$plugin_current_version = $Config->plugin_version;



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

  if ($license->key) {
    $activeLicense = true;
    $maskedKey = Utils::wps_mask_value($license->key);

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

  <h2><?php esc_attr_e('WP Shopify', 'wp-shopify' ); ?> <sup class="wps-version-pill wps-version-pill-sm"><?php echo $plugin_current_version; ?></sup></h2>

  <?php

  require_once plugin_dir_path( __FILE__ ) . 'wps-tabs.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-admin-notices.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-connect.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-settings.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-tools.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-updates.php';
  require_once plugin_dir_path( __FILE__ ) . 'wps-tab-content-help.php';

  ?>

  <!-- Used to validate any uninstall action the user takes -->
  <input type="hidden" name="wp-shopify-uninstall-nonce" id="wp-shopify-uninstall-nonce" value="<?php echo wp_create_nonce('wp-shopify-uninstall'); ?>">
  <input type="hidden" name="wp-shopify-cache-nonce" id="wp-shopify-cache-nonce" value="<?php echo wp_create_nonce('wp-shopify-cache'); ?>">

</div>
