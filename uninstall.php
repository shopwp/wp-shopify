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

use WPS\Factories\Processing\Database_Factory;

$Processing_Database = Database_Factory::build();

if ( is_multisite() ) {
	$Processing_Database->uninstall_plugin_multisite();

} else {
	$Processing_Database->uninstall_plugin();

}
