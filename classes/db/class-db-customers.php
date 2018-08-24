<?php

namespace WPS\DB;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Customers')) {

  class Customers extends \WPS\DB {

    public $table_name;
    public $version;
    public $primary_key;
		public $cache_group;


  	public function __construct() {

      global $wpdb;

      $this->table_name         			= WPS_TABLE_NAME_CUSTOMERS;
      $this->primary_key        			= 'id';
      $this->version            			= '1.0';
      $this->cache_group        			= 'wps_db_customers';

    }




  }

}
