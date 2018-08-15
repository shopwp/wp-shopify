<?php

namespace WPS;

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

if ( !current_user_can('activate_plugins') ) {
	exit;
}

include_once('lib/autoloader.php');

use WPS\Transients;
use WPS\Factories\Async_Processing_Database_Factory;
use WPS\Factories\DB_Settings_General_Factory;
use WPS\Factories\License_Factory;

$Async_Processing_Database = Async_Processing_Database_Factory::build();
$DB_Settings_General = DB_Settings_General_Factory::build();
$License = License_Factory::build();



if ($DB_Settings_General->is_free_tier() && $DB_Settings_General->is_pro_tier() ) {
	$DB_Settings_General->set_free_tier(0);

} else {
	$Async_Processing_Database->delete_posts();
	$Async_Processing_Database->drop_databases();
}


Transients::delete_all_cache();
