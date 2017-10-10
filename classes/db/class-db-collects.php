<?php

namespace WPS\DB;

use WPS\WS;
use WPS\Config;
use WPS\Utils;

class Collects extends \WPS\DB {

  public $table_name;
	public $version;
	public $primary_key;

  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name         = $wpdb->prefix . 'wps_collects';
    $this->primary_key        = 'id';
    $this->version            = '1.0';
    $this->cache_group        = 'wps_db_collects';

  }


  /*

  Get Columns

  */
	public function get_columns() {
    return array(
      'id'                   => '%d',
      'product_id'           => '%d',
      'collection_id'        => '%d',
      'featured'             => '%d',
      'position'             => '%d',
      'sort_value'           => '%d',
      'created_at'           => '%s',
      'updated_at'           => '%s'
    );
  }


  /*

  Get Column Defaults

  */
	public function get_column_defaults() {

    return array(
      'id'                   => 0,
      'product_id'           => '',
      'collection_id'        => '',
      'featured'             => '',
      'position'             => '',
      'sort_value'           => '',
      'created_at'           => date( 'Y-m-d H:i:s' ),
      'updated_at'           => date( 'Y-m-d H:i:s' )
    );

  }


  /*

  Insert Collects

  */
	public function insert_collects($collects) {

    $results = array();

    if (isset($collects) && $collects) {
      foreach ($collects as $key => $collect) {
        $results[] = $this->insert($collect, 'collect');
      }
    }

    return $results;

  }


  /*

  Update Collects

  */
	public function update_collects($product) {

    $productID = null;

    if (isset($product->id)) {
      $productID = $product->id;

    } else {
      $productID = $product->product_id;

    }


    $WS = new WS(new Config());
    $results = array();
    $collectsFromShopify = $WS->wps_ws_get_collects_from_product($productID);

    /*

    In order to handle image creation / deletions, we need to compare what's
    currently in the database with what gets sent back via the
    product/update webhook.

    */
    $currentCollectsForProduct = $this->get_rows('product_id', $productID);
    $currentCollectsForProductArray = Utils::wps_convert_object_to_array($currentCollectsForProduct);
    $collectsFromShopify = Utils::wps_convert_object_to_array($collectsFromShopify->collects);

    $collectsToAdd = Utils::wps_find_items_to_add($currentCollectsForProductArray, $collectsFromShopify, true);
    $collectsToDelete = Utils::wps_find_items_to_delete($currentCollectsForProductArray, $collectsFromShopify, true);


    if (count($collectsToAdd) > 0) {
      foreach ($collectsToAdd as $key => $newCollect) {
        $results['created'][] = $this->insert($newCollect, 'collect');
      }
    }

    if (count($collectsToDelete) > 0) {
      foreach ($collectsToDelete as $key => $oldCollect) {
        $results['deleted'][] = $this->delete($oldCollect['id']);
      }
    }

    return $results;

  }


  /*

  delete_collects_by_ids

  */
  public function delete_collects_by_ids($collects) {

    $collect_ids = Utils::extract_ids_from_object($collects);
    $collect_ids = Utils::convert_to_comma_string($collect_ids);

    return $this->delete_rows_in('id', $collect_ids);

  }


  /*

  Delete collects by product ID

  */
  public function delete_collects_by_product_id() {}



  /*

  Delete collects by collection ID

  */
  public function delete_collects_by_collection_id($collection_id) {}


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
      `id` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
      `product_id` bigint(100) DEFAULT NULL,
      `collection_id` bigint(100) DEFAULT NULL,
      `featured` tinyint(1) DEFAULT NULL,
      `position` int(20) DEFAULT NULL,
      `sort_value` int(20) DEFAULT NULL,
      `created_at` datetime,
      `updated_at` datetime,
      PRIMARY KEY  (`{$this->primary_key}`)
    ) ENGINE=InnoDB $collate";

  }


  /*

  Creates database table

  */
	public function create_table() {

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    if (!$this->table_exists($this->table_name)) {
      dbDelta( $this->create_table_query() );
    }

  }

}
