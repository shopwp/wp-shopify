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

$WS = new WS(new Config());

$WS->wps_uninstall_consumer(false);
$WS->wps_drop_databases();
