<?php

namespace WPS\DB;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class Customers extends \WPS\DB {

	public $table_name_suffix;
	public $table_name;
	public $version;
	public $primary_key;
	public $lookup_key;
	public $cache_group;
	public $type;

	public $default_customer_id;
	public $default_email;
	public $default_accepts_marketing;
	public $default_created_at;
	public $default_updated_at;
	public $default_first_name;
	public $default_last_name;
	public $default_orders_count;
	public $default_state;
	public $default_total_spent;
	public $default_last_order_id;
	public $default_note;
	public $default_verified_email;
	public $default_multipass_identifier;
	public $default_tax_exempt;
	public $default_phone;
	public $default_tags;
	public $default_last_order_name;
	public $default_default_address;
	public $default_addresses;


	public function __construct() {

		// Table info
		$this->table_name_suffix  						= WPS_TABLE_NAME_CUSTOMERS;
		$this->table_name         						= $this->get_table_name();
		$this->version            						= '1.0';
		$this->primary_key        						= 'id';
		$this->lookup_key        							= 'customer_id';
		$this->cache_group        						= 'wps_db_customers';
		$this->type        										= 'customer';

		// Defaults
		$this->default_customer_id						= 0;
		$this->default_email									= '';
		$this->default_accepts_marketing			= 0;
		$this->default_created_at							= date_i18n( 'Y-m-d H:i:s' );
		$this->default_updated_at							= date_i18n( 'Y-m-d H:i:s' );
		$this->default_first_name							= '';
		$this->default_last_name							= '';
		$this->default_orders_count						= 0;
		$this->default_state									= '';
		$this->default_total_spent						= '';
		$this->default_last_order_id					= 0;
		$this->default_note										= '';
		$this->default_verified_email					= 0;
		$this->default_multipass_identifier		= '';
		$this->default_tax_exempt							= 0;
		$this->default_phone									= '';
		$this->default_tags										= '';
		$this->default_last_order_name				= '';
		$this->default_default_address				= '';
		$this->default_addresses							= '';


	}





}
