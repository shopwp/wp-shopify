<?php

namespace WPS\DB;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Collects')) {

  class Collects extends \WPS\DB {

    public $table_name;
  	public $version;
  	public $primary_key;


  	public function __construct() {

      global $wpdb;

      $this->table_name         			= WPS_TABLE_NAME_COLLECTS;
      $this->primary_key        			= 'id';
      $this->version            			= '1.0';
      $this->cache_group        			= 'wps_db_collects';

    }


  	public function get_columns() {

      return [
        'id'                   => '%d',
				'collect_id'           => '%d',
        'product_id'           => '%d',
        'collection_id'        => '%d',
        'featured'             => '%d',
        'position'             => '%d',
        'sort_value'           => '%d',
        'created_at'           => '%s',
        'updated_at'           => '%s'
      ];

    }


  	public function get_column_defaults() {

      return [
        'id'                   => 0,
				'collect_id'           => 0,
        'product_id'           => '',
        'collection_id'        => '',
        'featured'             => '',
        'position'             => '',
        'sort_value'           => '',
        'created_at'           => date_i18n( 'Y-m-d H:i:s' ),
        'updated_at'           => date_i18n( 'Y-m-d H:i:s' )
      ];

    }


		public function insert_collect($collect) {
			return $this->insert( $this->rename_primary_key($collect, 'collect_id'), 'collect');
		}


    /*

    Insert Collects

    */
  	public function insert_collects($collects) {

      $results = [];

      if (Utils::array_not_empty($collects)) {

        foreach ($collects as $key => $collect) {
          $results[] = $this->insert_collect($collect);
        }

      }

      return $results;

    }


		/*

    Insert Collects

    */
  	public function delete_collects($collects) {

      $results = [];

      if (Utils::array_not_empty($collects)) {

        foreach ($collects as $key => $collect) {
          $results[] = $this->delete($collect->collect_id);
        }

      }

      return $results;

    }




		/*

		Gets collects by product ID

		*/
		public function get_collects_by_product_id($productID) {

			global $wpdb;

			$collects_table_name = WPS_TABLE_NAME_COLLECTS;

			$query = "SELECT * FROM $collects_table_name collects WHERE collects.product_id = %d;";

      return $wpdb->get_results(
        $wpdb->prepare($query, $productID)
      );

		}




		/*

		delete_collects_by_ids

		*/
		public function delete_collects_by_ids($collects) {

			$collect_ids = Utils::extract_ids_from_object($collects);
			$collect_ids = Utils::convert_to_comma_string($collect_ids);

			return $this->delete_rows_in('collect_id', $collect_ids);

		}


		/*

		Delete collects by collection ID

		*/
		public function delete_collects_from_collection_id($collection_id) {
			return $this->delete_rows('collection_id', $collection_id);
		}


		/*

    Delete collects from product ID

    */
		public function delete_collects_from_product_id($product_id) {
			return $this->delete_rows('product_id', $product_id);
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
				id bigint(100) unsigned NOT NULL AUTO_INCREMENT,
				collect_id bigint(100) unsigned NOT NULL DEFAULT 0,
				product_id bigint(100) DEFAULT NULL,
				collection_id bigint(100) DEFAULT NULL,
				featured tinyint(1) DEFAULT NULL,
				position int(20) DEFAULT NULL,
				sort_value int(20) DEFAULT NULL,
				created_at datetime,
				updated_at datetime,
				PRIMARY KEY  (id)
			) ENGINE=InnoDB $collate";

		}


  }

}
