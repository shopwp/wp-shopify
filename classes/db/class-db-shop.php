<?php

namespace WPS\DB;

class Shop extends \WPS\DB {

	public $table_name;
	public $version;
	public $primary_key;

  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name  		 = $wpdb->prefix . 'wps_shop';
    $this->primary_key 		 = 'id';
    $this->version     		 = '1.0';
		$this->cache_group     = 'wps_db_shop';

  }


  /*

  Get Columns

  */
	public function get_columns() {
    return array(
      'id'                          => '%d',
      'name'                        => '%s',
			'myshopify_domain'            => '%s',
      'shop_owner'                  => '%s',
      'phone'                       => '%s',
      'email'                       => '%s',
      'address1'                    => '%s',
      'address2'                    => '%s',
      'city'                        => '%s',
      'zip'                         => '%s',
      'country'                     => '%s',
      'country_code'                => '%s',
      'country_name'                => '%s',
      'currency'                    => '%s',
      'latitude'                    => '%f',
      'longitude'                   => '%f',
      'money_format'                => '%s',
      'money_with_currency_format'  => '%s',
      'weight_unit'                 => '%s',
      'primary_locale'              => '%s',
      'province'                    => '%s',
      'province_code'               => '%s',
      'timezone'                    => '%s',
			'created_at'                  => '%s',
      'updated_at'                  => '%s'
    );
  }


  /*

  Get Column Defaults

  */
	public function get_column_defaults() {
    return array(
      'id'                          => 0,
      'name'                        => '',
			'myshopify_domain'            => '',
      'shop_owner'                  => '',
      'phone'                       => '',
      'email'                       => '',
      'address1'                    => '',
      'address2'                    => '',
      'city'                        => '',
      'zip'                         => '',
      'country'                     => '',
      'country_code'                => '',
      'country_name'                => '',
      'currency'                    => '',
      'latitude'                    => '',
      'longitude'                   => '',
      'money_format'                => '',
      'money_with_currency_format'  => '',
      'weight_unit'                 => '',
      'primary_locale'              => '',
      'province'                    => '',
      'province_code'               => '',
      'timezone'                    => '',
			'created_at'                  => date( 'Y-m-d H:i:s' ),
      'updated_at'                  => date( 'Y-m-d H:i:s' )
    );
  }


  /*

  Get single shop info value

  */
	public function get_shop($column) {

    global $wpdb;

    // If not a string ...
    if (!is_string($column)) {
      return;
    }

    // If argument not apart of schema ...
    if (!array_key_exists($column, $this->get_columns()) ) {
      return;
    }

    $data = wp_cache_get($column, $this->cache_group);

    if (!$data) {

      $query = "SELECT $column FROM {$this->table_name};";
      $data = $wpdb->get_results($query);

      // Cache for 1 hour
      wp_cache_add($column, $data, $this->cache_group, 3600);

    }

    return $data;

  }


	/*

	insert_shop

	*/
	public function insert_shop($shopData) {

		global $wpdb;

		if ($this->get_by('id', $shopData['shop']['id'])) {

			$rowID = $this->get_by('id', $shopData['shop']['id']);
			$results = $this->update($rowID, $shopData['shop']);

		} else {
			$results = $this->insert($shopData['shop'], 'shop');
		}

		return $results;

	}


	/*

  Insert connection data

  */
  public function update_shop($shopData) {
    return $this->update($this->get_shop('id')[0]->id, $shopData);
  }


  /*

  Creates database table

  */
	public function create_table() {

    global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

    $query = "CREATE TABLE {$this->table_name} (
    `id` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) DEFAULT NULL,
		`myshopify_domain` varchar(255) DEFAULT NULL,
    `shop_owner` varchar(100) DEFAULT NULL,
    `phone` varchar(100) DEFAULT NULL,
    `email` varchar(100) DEFAULT NULL,
    `address1` varchar(100) DEFAULT NULL,
    `address2` varchar(100) DEFAULT NULL,
    `city` varchar(50) DEFAULT NULL,
    `zip` varchar(50) DEFAULT NULL,
    `country` varchar(50) DEFAULT NULL,
    `country_code` varchar(50) DEFAULT NULL,
    `country_name` varchar(50) DEFAULT NULL,
    `currency` varchar(50) DEFAULT NULL,
    `latitude` smallint(20) DEFAULT NULL,
    `longitude` smallint(20) DEFAULT NULL,
    `money_format` varchar(200) DEFAULT NULL,
    `money_with_currency_format` varchar(200) DEFAULT NULL,
    `weight_unit` varchar(20) DEFAULT NULL,
    `primary_locale` varchar(20) DEFAULT NULL,
    `province` varchar(20) DEFAULT NULL,
    `province_code` varchar(20) DEFAULT NULL,
    `timezone` varchar(200) DEFAULT NULL,
		`created_at` datetime,
    `updated_at` datetime,
    PRIMARY KEY (`{$this->primary_key}`)
  ) ENGINE=InnoDB DEFAULT CHARSET={$collate};";

		//
		// Create the table if it doesnt exist. Where the magic happens.
		//
		if (!$this->table_exists($this->table_name)) {
			dbDelta($query);
		}

  }


}
