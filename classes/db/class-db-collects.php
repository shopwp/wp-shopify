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
		public $lookup_key;
		public $cache_group;
		public $type;


  	public function __construct() {

      $this->table_name         	= WPS_TABLE_NAME_COLLECTS;
			$this->version            	= '1.0';
      $this->primary_key        	= 'id';
			$this->lookup_key        		= 'collect_id';
      $this->cache_group        	= 'wps_db_collects';
			$this->type        					= 'collect';

    }


		/*

		Table column name / formats

		Important: Used to determine when new columns are added

		*/
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


		/*

		Table default values

		*/
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


		/*

		The modify options used for inserting / updating / deleting

		*/
		public function modify_options($shopify_item, $item_lookup_key = WPS_PRODUCTS_LOOKUP_KEY) {

			return [
			  'item'									=> $shopify_item,
				'item_lookup_key'				=> $item_lookup_key,
				'item_lookup_value'			=> $shopify_item->id,
			  'prop_to_access'				=> 'collects',
			  'change_type'				    => 'collect'
			];

		}


		/*

		Mod before change

		*/
		public function mod_before_change($collect) {

			$collect_copy = $this->copy($collect);
			$collect_copy = $this->maybe_rename_to_lookup_key($collect_copy);

			return $collect_copy;

		}


		/*

		Insert single collect

		*/
		public function insert_collect($collect) {
			return $this->insert($collect);
		}


		/*

		Updates a single collect

		*/
		public function update_collect($collect) {
			return $this->update($this->lookup_key, $this->get_lookup_value($collect), $collect);
		}


		/*

		Deletes a single image

		The two params to delete_rows must match

		*/
		public function delete_collect($collect) {
			return $this->delete_rows($this->lookup_key, $this->get_lookup_value($collect));
		}


		/*

		Delete collects by collection ID

		*/
		public function delete_collects_from_collection_id($collection_id) {
			return $this->delete_rows(WPS_COLLECTIONS_LOOKUP_KEY, $collection_id);
		}


		/*

    Delete collects from product ID

    */
		public function delete_collects_from_product_id($product_id) {
			return $this->delete_rows(WPS_PRODUCTS_LOOKUP_KEY, $product_id);
		}


		/*

		delete_collects_by_ids

		*/
		public function delete_collects_by_ids($collects) {

			$collect_ids = Utils::extract_ids_from_object($collects);
			$collect_ids = Utils::convert_to_comma_string($collect_ids);

			return $this->delete_rows_in($this->lookup_key, $collect_ids);

		}


		public function get_collects_from_product_id($product_id) {
			return $this->get_rows(WPS_PRODUCTS_LOOKUP_KEY, $product_id);
		}


		public function get_collects_from_collection_id($collection_id) {
			return $this->get_rows(WPS_COLLECTIONS_LOOKUP_KEY, $collection_id);
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
