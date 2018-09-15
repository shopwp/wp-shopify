<?php

namespace WPS;

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

if ( !current_user_can('activate_plugins') ) {
	exit;
}

require_once('lib/autoloader.php'); // Our autoloader
require_once('vendor/autoload.php'); // Composer autoloader

use WPS\Transients;
use WPS\Factories\Async_Processing_Database_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\DB_Settings_License_Factory;
use WPS\Factories\WS_Settings_License_Factory;

$Async_Processing_Database 	= Async_Processing_Database_Factory::build();
$DB_Settings_General 				= DB_Settings_General_Factory::build();
$DB_Settings_License 				= DB_Settings_License_Factory::build();
$WS_Settings_License 				= WS_Settings_License_Factory::build();


if ($DB_Settings_General->is_free_tier() && $DB_Settings_General->is_pro_tier() ) {
	$DB_Settings_General->set_free_tier(0);

} else {


	$Async_Processing_Database->delete_posts();
	$Async_Processing_Database->drop_custom_tables();
	$Async_Processing_Database->drop_custom_migration_tables(WPS_TABLE_MIGRATION_SUFFIX);

}

Transients::delete_all_cache();
Transients::delete_custom_options();
