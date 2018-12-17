<?php

namespace WPS\API\Items;

use WPS\Messages;
use WPS\Utils;
use WPS\Utils\Data as Utils_Data;

if (!defined('ABSPATH')) {
	exit;
}


class Orders extends \WPS\API {


	public function __construct($DB_Settings_General, $DB_Settings_Syncing, $Shopify_API, $Processing_Orders) {
		$this->DB_Settings_General 		= $DB_Settings_General;
		$this->DB_Settings_Syncing 		= $DB_Settings_Syncing;
		$this->Shopify_API 						= $Shopify_API;
		$this->Processing_Orders			= $Processing_Orders;
	}


}
