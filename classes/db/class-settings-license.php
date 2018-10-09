<?php

namespace WPS\DB;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class Settings_License extends \WPS\DB {

	public $table_name_suffix;
	public $table_name;
	public $version;
	public $primary_key;
	public $lookup_key;
	public $cache_group;
	public $type;

	public $default_license_key;
	public $default_is_local;
	public $default_expires;
	public $default_site_count;
	public $default_checksum;
	public $default_customer_email;
	public $default_customer_name;
	public $default_item_name;
	public $default_license;
	public $default_license_limit;
	public $default_payment_id;
	public $default_success;
	public $default_nonce;
	public $default_activations_left;
	public $default_is_free;
	public $default_is_pro;
	public $default_beta_access;

	public function __construct() {

		$this->table_name_suffix  					= WPS_TABLE_NAME_SETTINGS_LICENSE;
		$this->table_name         					= $this->get_table_name();
		$this->version            					= '1.0';
		$this->primary_key        					= 'license_key';
		$this->lookup_key        						= 'license_key';
		$this->cache_group        					= 'wps_db_license';
		$this->type     										= 'settings_license';

		$this->default_license_key					= '';
		$this->default_is_local							= 0;
		$this->default_expires							= date_i18n( 'Y-m-d H:i:s' );
		$this->default_site_count						= 0;
		$this->default_checksum							= '';
		$this->default_customer_email				= '';
		$this->default_customer_name				= '';
		$this->default_item_name						= '';
		$this->default_license							= '';
		$this->default_license_limit				= 0;
		$this->default_payment_id						= 0;
		$this->default_success							= 0;
		$this->default_nonce								= '';
		$this->default_activations_left			= '';
		$this->default_is_free							= 0;
		$this->default_is_pro								= 0;
		$this->default_beta_access					= 0;

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
			'license_key'           => $this->default_license_key,
			'is_local'              => $this->default_is_local,
			'expires'               => $this->default_expires,
			'site_count'            => $this->default_site_count,
			'checksum'              => $this->default_checksum,
			'customer_email'        => $this->default_customer_email,
			'customer_name'         => $this->default_customer_name,
			'item_name'             => $this->default_item_name,
			'license'               => $this->default_license,
			'license_limit'         => $this->default_license_limit,
			'payment_id'            => $this->default_payment_id,
			'success'               => $this->default_success,
			'nonce'                 => $this->default_nonce,
			'activations_left'      => $this->default_activations_left,
			'is_free'      					=> $this->default_is_free,
			'is_pro'      					=> $this->default_is_pro,
			'beta_access'      			=> $this->default_beta_access
		];

	}


	/*

	Get single shop info value

	*/
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
			license_key varchar(100) NOT NULL DEFAULT '{$this->default_license_key}',
			is_local tinyint(1) unsigned DEFAULT '{$this->default_is_local}',
			expires datetime DEFAULT '{$this->default_expires}',
			site_count int(20) unsigned DEFAULT '{$this->default_site_count}',
			checksum varchar(100) DEFAULT '{$this->default_checksum}',
			customer_email varchar(100) DEFAULT '{$this->default_customer_email}',
			customer_name varchar(100) DEFAULT '{$this->default_customer_name}',
			item_name varchar(100) DEFAULT '{$this->default_item_name}',
			license varchar(100) DEFAULT '{$this->default_license}',
			license_limit int(20) DEFAULT '{$this->default_license_limit}',
			payment_id int(20) DEFAULT '{$this->default_payment_id}',
			success tinyint(1) DEFAULT '{$this->default_success}',
			nonce varchar(100) DEFAULT '{$this->default_nonce}',
			activations_left varchar(100) DEFAULT '{$this->default_activations_left}',
			is_free tinyint(1) unsigned DEFAULT '{$this->default_is_free}',
			is_pro tinyint(1) unsigned DEFAULT '{$this->default_is_pro}',
			beta_access tinyint(1) unsigned DEFAULT '{$this->default_beta_access}',
			PRIMARY KEY  (license_key)
		) ENGINE=InnoDB $collate";

	}


}
