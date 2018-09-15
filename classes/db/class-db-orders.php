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
		public $lookup_key;
		public $cache_group;
		public $type;


  	public function __construct() {

			$this->table_name         	= WPS_TABLE_NAME_ORDERS;
			$this->version            	= '1.0';
      $this->primary_key        	= 'id';
			$this->lookup_key        		= 'order_id';
      $this->cache_group        	= 'wps_db_orders';
			$this->type        					= 'order'; // Used for hook identifiers within low level db methods like insert, update, etc

    }




  }

}
