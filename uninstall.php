<?php

namespace WPS;

include_once('lib/autoloader.php');

use WPS\Config;
use WPS\WS;
use WPS\DB\Settings_General;

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

if ( !current_user_can('activate_plugins') ) {
	return;
}

$WS = new WS(new Config());
$plugin_settings = new Settings_General();



error_log('Uninstalling WP Shopify ...');

if ($plugin_settings->is_free_tier() && $plugin_settings->is_pro_tier()) {

	$freeTierDeactivated = $plugin_settings->set_free_tier(0);

	error_log('---- $freeTierDeactivated -----');
	error_log(print_r($freeTierDeactivated, true));
	error_log('---- /$freeTierDeactivated -----');

	error_log('NOT Uninstalling WP Shopify database content ...');

	return;

} else {

	error_log('Uninstalling WP Shopify database content ...');

	$WS->wps_uninstall_consumer(false);
	$WS->wps_drop_databases();
}
