<?php

namespace WPS\WS;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;

if (!class_exists('Orders')) {


  class Orders extends \WPS\WS {

		protected $WS;

  	public function __construct($DB_Orders, $DB_Settings_General, $Messages, $DB_Settings_Connection, $DB_Settings_Syncing, $Guzzle, $WS, $Async_Processing_Orders_Factory) {

			$this->DB_Orders 												= $DB_Orders;
			$this->DB_Settings_General 							= $DB_Settings_General;
			$this->DB_Settings_Connection						=	$DB_Settings_Connection;
			$this->DB_Settings_Syncing							=	$DB_Settings_Syncing;
			$this->Messages 												= $Messages;
			$this->WS																= $WS;
			$this->Async_Processing_Orders_Factory	= $Async_Processing_Orders_Factory;

			parent::__construct($Guzzle, $Messages, $DB_Settings_Connection, $DB_Settings_General, $DB_Settings_Syncing);

    }



		


  }

}
