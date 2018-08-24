<?php

namespace WPS\WS;

use WPS\Utils;
use WPS\Vendor\GuzzleHttp\Promise;

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Webhooks')) {

  class Webhooks extends \WPS\WS {

		protected $DB_Settings_Connection;
		protected $DB_Settings_General;
		protected $Webhooks;
		protected $Messages;
		protected $WS;


  	public function __construct($DB_Settings_Connection, $DB_Settings_General, $DB_Settings_Syncing, $Webhooks, $Messages, $Guzzle, $WS, $Async_Processing_Webhooks, $Async_Processing_Webhooks_Deletions) {

			$this->DB_Settings_Connection		= $DB_Settings_Connection;
			$this->DB_Settings_General			= $DB_Settings_General;
			$this->DB_Settings_Syncing			= $DB_Settings_Syncing;
			$this->Webhooks									= $Webhooks;
			$this->Messages									= $Messages;
			$this->WS												= $WS;

			$this->connection								= $this->DB_Settings_Connection->get();


			$this->Async_Processing_Webhooks 						= $Async_Processing_Webhooks;
			$this->Async_Processing_Webhooks_Deletions	= $Async_Processing_Webhooks_Deletions;

			parent::__construct($Guzzle, $Messages, $DB_Settings_Connection, $DB_Settings_General, $DB_Settings_Syncing);

    }






  }

}
