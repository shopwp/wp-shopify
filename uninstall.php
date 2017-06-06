<?php

namespace WPS;

include_once('lib/autoloader.php');

use WPS\Config;
use WPS\WS;


// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

if ( !current_user_can('activate_plugins') ) {
	return;
}


/*

TODO:
1. Delete 'code' from WP Shopify server

*/
$Config = new Config();
$WS = new WS($Config);
$WS->wps_uninstall_consumer(false);
$WS->wps_drop_databases();
