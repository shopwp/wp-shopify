<?php

use WPS\Factories;

$Backend                  = Factories\Backend_Factory::build();
$DB_Settings_Connection   = Factories\DB\Settings_Connection_Factory::build();
$DB_Settings_License      = Factories\DB\Settings_License_Factory::build();
$DB_Settings_General      = Factories\DB\Settings_General_Factory::build();

$connection               = $DB_Settings_Connection->get();
$license                  = $DB_Settings_License->get();
$general                  = $DB_Settings_General->get();

$has_connection           = $DB_Settings_Connection->has_connection();

if ( $Backend->is_admin_settings_page( $Backend->is_valid_admin_page()->id ) ) {

  $active_tab       = $Backend->get_active_tab($_GET);
  $active_sub_nav   = $Backend->get_active_sub_tab($_GET);

}

?>

<div class="wrap wps-admin-wrap">

  <h2>
    <?php esc_attr_e( $DB_Settings_General->plugin_nice_name(), WPS_PLUGIN_TEXT_DOMAIN); ?>
    <sup class="wps-version-pill wps-version-pill-sm"><?= WPS_NEW_PLUGIN_VERSION; ?></sup>
  </h2>

  <?php

  require plugin_dir_path( __FILE__ ) . 'wps-tabs.php';
  require plugin_dir_path( __FILE__ ) . 'wps-admin-notices.php';
  require plugin_dir_path( __FILE__ ) . 'wps-tab-content-connect.php';
  require plugin_dir_path( __FILE__ ) . 'wps-tab-content-settings.php';
  require plugin_dir_path( __FILE__ ) . 'wps-tab-content-tools.php';
  require plugin_dir_path( __FILE__ ) . 'wps-tab-content-license.php';
  require plugin_dir_path( __FILE__ ) . 'wps-tab-content-help.php';
  require plugin_dir_path( __FILE__ ) . 'wps-tab-content-misc.php';

  ?>

</div>
