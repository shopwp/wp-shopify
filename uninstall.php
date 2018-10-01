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

use WPS\Factories\Async_Processing_Database_Factory;

$Async_Processing_Database = Async_Processing_Database_Factory::build();


if ( is_multisite() ) {
	$Async_Processing_Database->uninstall_plugin_multisite();

} else {
	$Async_Processing_Database->uninstall_plugin();

}
