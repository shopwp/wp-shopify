<?php

namespace WPS\API\Items;


if (!defined('ABSPATH')) {
	exit;
}


class Webhooks extends \WPS\API {

	public function __construct($DB_Settings_Syncing, $Webhooks, $Processing_Webhooks, $Processing_Webhooks_Deletions, $Shopify_API) {

		$this->DB_Settings_Syncing									= $DB_Settings_Syncing;
		$this->Webhooks															= $Webhooks;
		$this->Processing_Webhooks 									= $Processing_Webhooks;
		$this->Processing_Webhooks_Deletions				= $Processing_Webhooks_Deletions;
		$this->Shopify_API													= $Shopify_API;

	}


}
