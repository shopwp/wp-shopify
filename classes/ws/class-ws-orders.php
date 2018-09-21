<?php

namespace WPS\WS;

if (!defined('ABSPATH')) {
	exit;
}

use WPS\Utils;
use WPS\Messages;

if (!class_exists('Orders')) {


  class Orders extends \WPS\WS {

		protected $DB_Orders;
		protected $DB_Settings_General;
		protected $DB_Settings_Syncing;
		protected $Async_Processing_Orders_Factory;
		protected $Shopify_API;

  	public function __construct($DB_Orders, $DB_Settings_General, $DB_Settings_Syncing, $Async_Processing_Orders_Factory, $Shopify_API) {

			$this->DB_Orders 												= $DB_Orders;
			$this->DB_Settings_General 							= $DB_Settings_General;
			$this->DB_Settings_Syncing							=	$DB_Settings_Syncing;
			$this->Async_Processing_Orders_Factory	= $Async_Processing_Orders_Factory;
			$this->Shopify_API											= $Shopify_API;

    }





  }

}
