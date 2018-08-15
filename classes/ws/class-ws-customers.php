<?php

namespace WPS\WS;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Customers')) {


  class Customers extends \WPS\WS {

		protected $DB_Customers;
		protected $DB_Settings_Connection;
		protected $DB_Settings_Syncing;
		protected $DB_Settings_General;
		protected $Messages;
		protected $WS;

  	public function __construct($DB_Customers, $DB_Settings_Connection, $DB_Settings_Syncing, $DB_Settings_General, $Messages, $Guzzle, $WS, $Async_Processing_Customers) {

			$this->DB_Customers									= $DB_Customers;
			$this->DB_Settings_Connection				= $DB_Settings_Connection;
			$this->DB_Settings_Syncing					= $DB_Settings_Syncing;
			$this->DB_Settings_General					= $DB_Settings_General;
			$this->Messages											= $Messages;
			$this->Guzzle												= $Guzzle;
			$this->WS														= $WS;
			$this->Async_Processing_Customers		=	$Async_Processing_Customers;

			parent::__construct($Guzzle, $Messages, $DB_Settings_Connection, $DB_Settings_General, $DB_Settings_Syncing);

    }



		


  }


}
