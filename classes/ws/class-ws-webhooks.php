<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Messages;


if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Webhooks')) {

  class Webhooks extends \WPS\WS {

		protected $DB_Settings_Syncing;
		protected $Webhooks;
		protected $Async_Processing_Webhooks;
		protected $Async_Processing_Webhooks_Deletions;
		protected $HTTP;

  	public function __construct($DB_Settings_Syncing, $Webhooks, $Async_Processing_Webhooks, $Async_Processing_Webhooks_Deletions, $HTTP) {

			$this->DB_Settings_Syncing									= $DB_Settings_Syncing;
			$this->Webhooks															= $Webhooks;
			$this->Async_Processing_Webhooks 						= $Async_Processing_Webhooks;
			$this->Async_Processing_Webhooks_Deletions	= $Async_Processing_Webhooks_Deletions;
			$this->HTTP																	= $HTTP;

    }





  }

}
