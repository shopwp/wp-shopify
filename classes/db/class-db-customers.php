<?php

namespace WPS\DB;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Customers')) {

  class Customers extends \WPS\DB {

    public $table_name;
  	public $version;
  	public $primary_key;
		public $lookup_key;
		public $cache_group;
		public $type;


  	public function __construct() {

      $this->table_name         	= WPS_TABLE_NAME_CUSTOMERS;
			$this->version            	= '1.0';
      $this->primary_key        	= 'id';
      $this->lookup_key        		= 'customer_id';
      $this->cache_group        	= 'wps_db_customers';
			$this->type        					= 'customer';

    }




  }

}
