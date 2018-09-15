<?php

namespace WPS\DB;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Options')) {

  class Options extends \WPS\DB {

    public $table_name;
  	public $version;
  	public $primary_key;
		public $lookup_key;
		public $cache_group;
		public $type;


  	public function __construct() {

      $this->table_name         	= WPS_TABLE_NAME_OPTIONS;
			$this->version            	= '1.0';
      $this->primary_key        	= 'id';
      $this->lookup_key        		= 'option_id';
      $this->cache_group        	= 'wps_db_options';
			$this->type        					= 'option';

    }


		/*

		Table column name / formats

		Important: Used to determine when new columns are added

		*/
  	public function get_columns() {

      return [
        'id'                        => '%d',
				'option_id'                 => '%d',
        'product_id'                => '%d',
        'name'                      => '%s',
        'position'                  => '%d',
        'values'                    => '%s'
      ];

    }


		/*

		Table default values

		*/
  	public function get_column_defaults() {

			return [
        'id'                        => 0,
				'option_id'                 => 0,
        'product_id'                => 0,
        'name'                      => '',
        'position'                  => 0,
        'values'                    => ''
      ];

    }


		/*

		The modify options used for inserting / updating / deleting

		*/
		public function modify_options($shopify_item, $item_lookup_key = WPS_PRODUCTS_LOOKUP_KEY) {

			return [
			  'item'											=> $shopify_item,
				'item_lookup_key'						=> $item_lookup_key,
				'item_lookup_value'					=> $shopify_item->id,
			  'prop_to_access'						=> 'options',
			  'change_type'				    		=> 'option'
			];

		}


		/*

		Mod before change

		*/
		public function mod_before_change($option) {

			$option_copy = $this->copy($option);
			$option_copy = $this->maybe_rename_to_lookup_key($option_copy);

			return $option_copy;

		}


		/*

		Inserts a single option

		*/
		public function insert_option($option) {
			return $this->insert($option);
		}


		/*

		Updates a single option

		*/
		public function update_option($option) {
			return $this->update($this->lookup_key, $this->get_lookup_value($option), $option);
		}


		/*

		Deletes a single option

		*/
		public function delete_option($option) {
			return $this->delete_rows($this->lookup_key, $this->get_lookup_value($option));
		}


		/*

		Delete options from product ID

		*/
		public function delete_options_from_product_id($product_id) {
			return $this->delete_rows(WPS_PRODUCTS_LOOKUP_KEY, $product_id);
		}


		/*

		Gets all options associated with a given product, by product id

		*/
		public function get_options_from_product_id($product_id) {
			return $this->get_rows(WPS_PRODUCTS_LOOKUP_KEY, $product_id);
		}


    /*

    Get Product Options

    */
    public function get_options_from_post_id($postID = null) {

      global $wpdb;

      if ($postID === null) {
        $postID = get_the_ID();
      }

      if (get_transient('wps_product_single_options_' . $postID)) {
        $results = get_transient('wps_product_single_options_' . $postID);

      } else {

        $query = "SELECT options.* FROM " . WPS_TABLE_NAME_PRODUCTS . " as products INNER JOIN " . WPS_TABLE_NAME_OPTIONS . " as options ON products.product_id = options.product_id WHERE products.post_id = %d";

        $results = $wpdb->get_results( $wpdb->prepare($query, $postID) );

        set_transient('wps_product_single_options_' . $postID, $results);

      }

      return $results;

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
				option_id bigint(100) unsigned NOT NULL DEFAULT 0,
        product_id bigint(100) DEFAULT NULL,
        name varchar(100) DEFAULT NULL,
        position int(20) DEFAULT NULL,
        `values` longtext DEFAULT NULL,
        PRIMARY KEY  (id)
      ) ENGINE=InnoDB $collate";

    }


  }

}
