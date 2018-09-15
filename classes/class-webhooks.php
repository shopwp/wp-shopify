<?php

namespace WPS;

use WPS\Utils;
use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Webhooks')) {

	class Webhooks {

		private $DB_Settings_Connection;
		private $DB_Settings_General;
		private $HTTP;

		public function __construct($DB_Settings_Connection, $DB_Settings_General, $HTTP) {

			$this->DB_Settings_Connection 	= $DB_Settings_Connection;
			$this->DB_Settings_General 			= $DB_Settings_General;
			$this->HTTP 										= $HTTP;

		}




	}

}
