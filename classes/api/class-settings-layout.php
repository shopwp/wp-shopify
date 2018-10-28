<?php

namespace WPS\API;


if (!defined('ABSPATH')) {
	exit;
}


class Settings_Layout extends \WPS\API {

  public $DB_Settings_General;

	public function __construct($DB_Settings_General) {
		$this->DB_Settings_General = $DB_Settings_General;
	}

	/*

	Hooks

	*/
	public function hooks() {


	}


  /*

  Init

  */
  public function init() {
		$this->hooks();
  }


}
