<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Messages;

if (!defined('ABSPATH')) {
	exit;
}


class Customers extends \WPS\WS {

	protected $DB_Customers;
	protected $DB_Settings_Syncing;
	protected $DB_Settings_General;
	protected $Async_Processing_Customers;
	protected $Shopify_API;

	public function __construct($DB_Customers, $DB_Settings_Syncing, $DB_Settings_General, $Async_Processing_Customers, $Shopify_API) {

		$this->DB_Customers									= $DB_Customers;
		$this->DB_Settings_Syncing					= $DB_Settings_Syncing;
		$this->DB_Settings_General					= $DB_Settings_General;
		$this->Async_Processing_Customers		=	$Async_Processing_Customers;
		$this->Shopify_API									=	$Shopify_API;

	}




}
