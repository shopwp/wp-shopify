<?php

namespace WPS;

if (!defined('ABSPATH')) {
	exit;
}

if ( !class_exists('Deactivator') ) {

	class Deactivator {


		/*

		Initialize the class and set its properties.

		*/
		public function __construct() {

		}


		/*

		Things to do on plugin deactivation

		*/
		public function deactivate() {
			delete_option('rewrite_rules');
		}

		public function init() {
			register_deactivation_hook(WPS_PLUGIN_BASENAME, [$this, 'deactivate']);
		}

	}

}
