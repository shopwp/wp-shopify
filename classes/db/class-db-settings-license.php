<?php

namespace WPS\DB;


class Settings_License extends \WPS\DB {

  public $table_name;
	public $version;
	public $primary_key;


  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name         = $wpdb->prefix . 'wps_settings_license';
    $this->primary_key        = 'key';
    $this->version            = '1.0';
    $this->cache_group        = 'wps_db_license';

  }


  /*

  Get Columns

  */
	public function get_columns() {
    return array(
      'key'                   => '%s',
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
      'price_id'              => '%d'
    );
  }


  /*

  Get Column Defaults

  */
	public function get_column_defaults() {
    return array(
      'key'                   => '',
      'is_local'              => '',
      'expires'               => date( 'Y-m-d H:i:s' ),
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
      'price_id'              => ''
    );
  }


  /*

  Get single shop info value

  */
	public function get_license($column) {

  }


  /*

	insert_license

	*/
	public function insert_license($licenseData) {

    return $this->insert($licenseData, 'license');

	}


  /*

  delete_license

  */
  public function delete_license($licenseKey) {

    return $this->delete('key');

  }


  /*

  Creates a table query string

  */
  public function create_table_query() {

    global $wpdb;

    $collate = '';

    if ( $wpdb->has_cap('collation') ) {
      $collate = $wpdb->get_charset_collate();
    }

    return "CREATE TABLE `{$this->table_name}` (
      `key` varchar(100) NOT NULL DEFAULT '',
      `is_local` tinyint(1) unsigned NOT NULL,
      `expires` datetime,
      `site_count` int(20) unsigned DEFAULT NULL,
      `checksum` varchar(100) DEFAULT NULL,
      `customer_email` varchar(100) DEFAULT NULL,
      `customer_name` varchar(100) DEFAULT NULL,
      `item_name` varchar(100) DEFAULT NULL,
      `license` varchar(100) DEFAULT NULL,
      `license_limit` int(20) DEFAULT NULL,
      `payment_id` int(20) DEFAULT NULL,
      `success` tinyint(1) DEFAULT NULL,
      `nonce` varchar(100) DEFAULT NULL,
      `activations_left` varchar(100) DEFAULT NULL,
      `price_id` varchar(100) DEFAULT NULL,
      PRIMARY KEY  (`{$this->primary_key}`)
    ) ENGINE=InnoDB $collate";

  }


  /*

  Creates database table

  */
	public function create_table() {

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    if ( !$this->table_exists($this->table_name) ) {
      dbDelta( $this->create_table_query() );
    }

  }

}
