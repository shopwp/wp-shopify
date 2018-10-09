<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}


class I18N {

	/*

	Load the plugin text domain for translation.

	*/
	public function load_textdomain() {
		load_plugin_textdomain(WPS_PLUGIN_TEXT_DOMAIN, false, dirname(dirname(plugin_basename(__FILE__))) . WPS_LANGUAGES_FOLDER);
	}


	/*

	init

	*/
	public function init() {
		add_action('plugins_loaded', array($this, 'load_textdomain') );
	}

}
