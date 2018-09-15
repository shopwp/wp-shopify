<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Customers')) {

  class Customers extends \WPS\WS {

		protected $DB_Customers;
		protected $DB_Settings_Syncing;
		protected $DB_Settings_General;
		protected $Async_Processing_Customers;
		protected $HTTP;

  	public function __construct($DB_Customers, $DB_Settings_Syncing, $DB_Settings_General, $Async_Processing_Customers, $HTTP) {

			$this->DB_Customers									= $DB_Customers;
			$this->DB_Settings_Syncing					= $DB_Settings_Syncing;
			$this->DB_Settings_General					= $DB_Settings_General;
			$this->Async_Processing_Customers		=	$Async_Processing_Customers;
			$this->HTTP													=	$HTTP;

    }




  }


}
