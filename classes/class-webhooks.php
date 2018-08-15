<?php

namespace WPS;

use WPS\Utils;
use WPS\DB\Settings_Connection;
use WPS\DB\Settings_General;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Webhooks')) {

	class Webhooks {

	  private $Messages;
		private $DB_Settings_Connection;
		private $DB_Settings_General;
		private $WS;

		public function __construct($Messages, $DB_Settings_Connection, $DB_Settings_General, $WS) {

			$this->Messages 								= $Messages;
			$this->DB_Settings_Connection 	= $DB_Settings_Connection;
			$this->DB_Settings_General 			= $DB_Settings_General;
			$this->WS 											= $WS;

		}




	}

}
