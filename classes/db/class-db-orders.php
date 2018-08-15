<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\Transients;
use WPS\CPT;


if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Orders')) {

  class Orders extends \WPS\DB {

    public $table_name;
    public $version;
    public $primary_key;
		public $cache_group;


  	public function __construct() {

			$this->table_name         			= WPS_TABLE_NAME_ORDERS;
      $this->primary_key        			= 'id';
      $this->version            			= '1.0';
      $this->cache_group        			= 'wps_db_orders';

    }


		

  }

}
