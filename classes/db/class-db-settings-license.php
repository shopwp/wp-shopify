<?php

namespace WPS\DB;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Settings_License')) {

  class Settings_License extends \WPS\DB {

    public $table_name;
  	public $version;
  	public $primary_key;
		public $lookup_key;
		public $cache_group;
		public $type;


  	public function __construct() {

      $this->table_name         = WPS_TABLE_NAME_SETTINGS_LICENSE;
			$this->version            = '1.0';
      $this->primary_key        = 'license_key';
      $this->lookup_key        	= 'license_key';
      $this->cache_group        = 'wps_db_license';
			$this->type     					= 'settings_license';

    }


  	public function get_columns() {

      return [
        'license_key'           => '%s',
        'is_local'              => '%d',
        'expires'               => '%s',
        'site_count'            => '%d',
        'checksum'              => '%s',
        'customer_email'        => '%s',
        'customer_name'         => '%s',
        'item_name'             => '%s',
        'license'               => '%s',
        'license_limit'         => '%d',
        'payment_id'            => '%d',
        'success'               => '%d',
        'nonce'                 => '%s',
        'activations_left'      => '%s',
				'is_free'      					=> '%s',
				'is_pro'      					=> '%s',
				'beta_access'      			=> '%s',
      ];

    }


  	public function get_column_defaults() {

			return [
        'license_key'           => '',
        'is_local'              => '',
        'expires'               => date_i18n( 'Y-m-d H:i:s' ),
        'site_count'            => '',
        'checksum'              => '',
        'customer_email'        => '',
        'customer_name'         => '',
        'item_name'             => '',
        'license'               => '',
        'license_limit'         => '',
        'payment_id'            => '',
        'success'               => '',
        'nonce'                 => '',
        'activations_left'      => '',
				'is_free'      					=> '',
				'is_pro'      					=> '',
				'beta_access'      			=> ''
      ];

    }


    /*

    Get single shop info value

    */
  	// public function get_license() {
		//
		// 	$license_key = $this->get_column_single('license_key');
		//
		// 	if ( Utils::array_not_empty($license_key) && isset($license_key[0]->license_key) ) {
		// 		return $license_key[0]->license_key;
		//
		// 	} else {
		// 		return false;
		// 	}
		//
    // }
		//
		//
		public function get_license() {
			return $this->get();
		}


    /*

  	insert_license

  	*/
  	public function insert_license($licenseData) {
      return $this->insert($licenseData);
  	}


    /*

    Deletes a license

    */
    public function delete_license() {
      return $this->truncate();
    }


    /*

    Creates a table query string

    */
    public function create_table_query($table_name = false) {

			if ( !$table_name ) {
				$table_name = $this->table_name;
			}

      $collate = $this->collate();

      return "CREATE TABLE $table_name (
        license_key varchar(100) NOT NULL,
        is_local tinyint(1) unsigned DEFAULT NULL,
        expires datetime,
        site_count int(20) unsigned DEFAULT NULL,
        checksum varchar(100) DEFAULT NULL,
        customer_email varchar(100) DEFAULT NULL,
        customer_name varchar(100) DEFAULT NULL,
        item_name varchar(100) DEFAULT NULL,
        license varchar(100) DEFAULT NULL,
        license_limit int(20) DEFAULT NULL,
        payment_id int(20) DEFAULT NULL,
        success tinyint(1) DEFAULT NULL,
        nonce varchar(100) DEFAULT NULL,
        activations_left varchar(100) DEFAULT NULL,
				is_free tinyint(1) unsigned DEFAULT NULL,
				is_pro tinyint(1) unsigned DEFAULT NULL,
				beta_access tinyint(1) unsigned DEFAULT NULL,
        PRIMARY KEY  (license_key)
      ) ENGINE=InnoDB $collate";

    }


  }

}
