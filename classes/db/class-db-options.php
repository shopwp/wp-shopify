<?php

namespace WPS\DB;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Options')) {

  class Options extends \WPS\DB {

    public $table_name;
		public $primary_key;
  	public $version;
		public $cache_group;


  	public function __construct() {

      $this->table_name         				= WPS_TABLE_NAME_OPTIONS;
      $this->primary_key        				= 'id';
      $this->version            				= '1.0';
      $this->cache_group        				= 'wps_db_options';

    }


		/*

		Important: Used to determine when new columns are added

		*/
  	public function get_columns() {

      return [
        'id'                        => '%d',
        'product_id'                => '%d',
        'name'                      => '%s',
        'position'                  => '%d',
        'values'                    => '%s'
      ];

    }


  	public function get_column_defaults() {

			return [
        'id'                        => 0,
        'product_id'                => 0,
        'name'                      => '',
        'position'                  => 0,
        'values'                    => ''
      ];

    }


    /*

    Get single shop info value

    */
  	public function insert_option($product) {

      $results = [];
			$product = Utils::convert_array_to_object($product);

      if (isset($product->options) && $product->options) {

        foreach ($product->options as $key => $option) {

					$result = $this->insert($option, 'option');

					if (is_wp_error($result)) {
						return $result;
					}

					$results[] = $result;

        }

      }

			return $results;

    }


    /*

    Get single shop info value

    */
  	public function insert_options($products) {

      $results = [];

			$products = Utils::wrap_in_array($products);

      foreach ($products as $key => $product) {
				$results[] = $this->insert_option($product);
      }

      return $results;

    }


    /*

    Delete Option

    */
  	public function delete_option($product) {

      $results = [];

      if (count($product->options) > 0) {

        foreach ($product->options as $key => $option) {
          $results[] = $this->update($option->id, $option);
        }

      } else {
        $results[] = $this->delete_rows('product_id', $product->id);

      }

      return $results;

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

		Delete options from product ID

		*/
		public function delete_options_from_product_id($product_id) {
			return $this->delete_rows('product_id', $product_id);
		}









		/*

		update_option

		*/
		public function update_options_from_product($product) {

			$results = [];
			$optionsFromShopify = $product->options;
			$currentOptions = $this->get_rows('product_id', $product->id);

			$optionsToAdd = Utils::wps_find_items_to_add($currentOptions, $optionsFromShopify, true);
			$optionsToDelete = Utils::wps_find_items_to_delete($currentOptions, $optionsFromShopify, true);

			if (count($optionsToAdd) > 0) {

				foreach ($optionsToAdd as $key => $newOption) {
					$results['created'][] = $this->insert($newOption, 'option');
				}

			}

			if (count($optionsToDelete) > 0) {

				foreach ($optionsToDelete as $key => $oldOption) {
					$results['deleted'][] = $this->delete($oldOption->id);
				}

			}

			foreach ($product->options as $key => $option) {
				$results['updated'] = $this->update($option->id, $option);
			}

			return $results;

		}


    /*

    Creates a table query string

    */
    public function create_table_query($table_name = false) {

      global $wpdb;

			if (!$table_name) {
				$table_name = $this->table_name;
			}

      $collate = '';

      if ( $wpdb->has_cap('collation') ) {
        $collate = $wpdb->get_charset_collate();
      }

      return "CREATE TABLE $table_name (
        id bigint(100) unsigned NOT NULL DEFAULT 0,
        product_id bigint(100) DEFAULT NULL,
        name varchar(100) DEFAULT NULL,
        position int(20) DEFAULT NULL,
        `values` longtext DEFAULT NULL,
        PRIMARY KEY  (id)
      ) ENGINE=InnoDB $collate";

    }


		/*

		Migrate insert into query

		*/
		public function migration_insert_into_query() {

			return $this->query('INSERT INTO ' . $this->table_name . WPS_TABLE_MIGRATION_SUFFIX . '(`id`, `product_id`, `name`, `position`, `values`) SELECT `id`, `product_id`, `name`, `position`, `values` FROM ' . $this->table_name);

		}


    /*

    Creates database table

    */
  	public function create_table() {

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      if (!$this->table_exists($this->table_name)) {
        dbDelta( $this->create_table_query($this->table_name) );
				set_transient('wp_shopify_table_exists_' . $this->table_name, 1);
      }

    }

  }

}
