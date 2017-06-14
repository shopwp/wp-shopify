<?php

namespace WPS\DB;

class Inventory extends \WPS\DB {

  public $table_name;
	public $version;
	public $primary_key;

  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name         = $wpdb->prefix . 'wps_inventory';
    $this->primary_key        = 'id';
    $this->version            = '1.0';
    $this->cache_group        = 'wps_db_inventory';

  }


  /*

  Get Columns

  */
	public function get_columns() {
    return array(
      'id'                      => '%d',
      'variant_id'              => '%d',
      'sku'                     => '%s',
      'inventory_policy'        => '%s',
      'inventory_quantity'      => '%d',
      'inventory_management'    => '%s',
      'requires_shipping'       => '%d',
      'fulfillment_service'     => '%s',
      'barcode'                 => '%s'
    );
  }


  /*

  Get Column Defaults

  */
	public function get_column_defaults() {
    return array(
      'id'                      => 0,
      'variant_id'              => '',
      'sku'                     => '',
      'inventory_policy'        => '',
      'inventory_quantity'      => '',
      'inventory_management'    => '',
      'requires_shipping'       => '',
      'fulfillment_service'     => '',
      'barcode'                 => ''
    );
  }


  /*

  Get single shop info value

  */
  public function insert_inventory($products) {

    foreach ($products as $key => $product) {

      foreach ($product->variants as $key => $variant) {
        $results[] = $this->insert($variant, 'variant');
      }

    }

    return $results;

  }


  /*

  Creates database table

  */
	public function create_table() {

    global $wpdb;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$collate = '';

		if ( $wpdb->has_cap('collation') ) {
			$collate = $wpdb->get_charset_collate();
		}

    $query = "CREATE TABLE `{$this->table_name}` (
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `variant_id` bigint(20) DEFAULT NULL,
      `sku` varchar(255) DEFAULT NULL,
      `inventory_policy` varchar(100) DEFAULT NULL,
      `inventory_quantity` bigint(20) DEFAULT NULL,
      `inventory_management` varchar(100) DEFAULT NULL,
      `requires_shipping` tinyint(1) DEFAULT NULL,
      `fulfillment_service` varchar(100) DEFAULT NULL,
      `barcode` varchar(100) DEFAULT NULL,
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
