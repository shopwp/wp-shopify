<?php

namespace WPS\DB;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


if (!class_exists('Variants')) {

  class Variants extends \WPS\DB {

    public $table_name;
  	public $version;
  	public $primary_key;
		public $lookup_key;
		public $cache_group;
		public $type;


  	public function __construct() {

			$this->table_name         				= WPS_TABLE_NAME_VARIANTS;
			$this->version            				= '1.0';
      $this->primary_key        				= 'id';
			$this->lookup_key        					= 'variant_id';
      $this->cache_group        				= 'wps_db_variants';
			$this->type        								= 'variant';

    }


		/*

		Table column name / formats

		Important: Used to determine when new columns are added

		*/
  	public function get_columns() {

      return [
        'id'                        => '%d',
				'variant_id'                => '%d',
        'product_id'                => '%d',
        'image_id'                  => '%d',
        'title'                     => '%s',
        'price'                     => '%f',
        'compare_at_price'          => '%f',
        'position'                  => '%d',
        'option1'                   => '%s',
        'option2'                   => '%s',
        'option3'                   => '%s',
        'taxable'                   => '%d',
        'weight'                    => '%d',
        'weight_unit'               => '%s',
        'sku'                       => '%s',
        'inventory_policy'          => '%s',
        'inventory_quantity'        => '%d',
        'old_inventory_quantity'    => '%d',
        'inventory_management'      => '%s',
        'requires_shipping'         => '%d',
        'fulfillment_service'       => '%s',
        'barcode'                   => '%s',
        'created_at'                => '%s',
        'updated_at'                => '%s',
				'admin_graphql_api_id'			=> '%s'
      ];

    }


		/*

		Table default values

		*/
  	public function get_column_defaults() {

      return [
        'id'                        => '',
				'variant_id'                => '',
        'product_id'                => '',
        'image_id'                  => '',
        'title'                     => '',
        'price'                     => 0,
        'compare_at_price'          => 0,
        'position'                  => '',
        'option1'                   => '',
        'option2'                   => '',
        'option3'                   => '',
        'taxable'                   => '',
        'weight'                    => '',
        'weight_unit'               => '',
        'sku'                       => '',
        'inventory_policy'          => '',
        'inventory_quantity'        => '',
        'old_inventory_quantity'    => '',
        'inventory_management'      => '',
        'requires_shipping'         => '',
        'fulfillment_service'       => '',
        'barcode'                   => '',
        'created_at'                => date_i18n( 'Y-m-d H:i:s' ),
        'updated_at'                => date_i18n( 'Y-m-d H:i:s' ),
				'admin_graphql_api_id'			=> ''
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
			  'prop_to_access'						=> 'variants',
			  'change_type'				    		=> 'variant'
			];

		}


		/*

		Mod before change

		*/
		public function mod_before_change($variant) {

			$variant_copy = $this->copy($variant);
			$variant_copy = $this->maybe_rename_to_lookup_key($variant_copy);

			return $variant_copy;

		}


		/*

		Inserts a single variant

		$variant comes straight from Shopify

		*/
		public function insert_variant($variant) {
			return $this->insert($variant);
		}


		/*

		Updates a single variant

		$variant comes straight from Shopify

		*/
		public function update_variant($variant) {
			return $this->update($this->lookup_key, $this->get_lookup_value($variant), $variant);
		}


		/*

		Deletes a single variant

		$variant comes straight from Shopify

		*/
		public function delete_variant($variant) {
			return $this->delete_rows($this->lookup_key, $this->get_lookup_value($variant));
		}


		/*

	  Delete variants from product ID

	  */
	  public function delete_variants_from_product_id($product_id) {
			return $this->delete_rows(WPS_PRODUCTS_LOOKUP_KEY, $product_id);
	  }


		/*

		Gets variants based on a Shopify product id

		*/
		public function get_variants_from_product_id($product_id) {
			return $this->get_rows(WPS_PRODUCTS_LOOKUP_KEY, $product_id);
		}


		/*

		Get Product Variants

		Note: only gets variants that are in stock, perhaps we change

		*/
		public function get_in_stock_variants_from_post_id($post_id = null) {

			global $wpdb;

			if ($post_id === null) {
				$post_id = get_the_ID();
			}

			if (get_transient('wps_product_single_variants_in_stock_' . $post_id)) {
				return get_transient('wps_product_single_variants_in_stock_' . $post_id);
			}

			$query = $this->get_variants_from_post_id_query();
			$query_prepared = $wpdb->prepare($query, $post_id);

			$variants = $wpdb->get_results($query_prepared);

			$variants = Utils::product_inventory(false, $variants);

			set_transient('wps_product_single_variants_in_stock_' . $post_id, $variants);

			return $variants;

		}


		/*

		Get Product Variants

		Note: only gets variants that are in stock, perhaps we change

		*/
		public function get_all_variants_from_post_id($post_id = null) {

			global $wpdb;

			if ( $post_id === null ) {
				$post_id = get_the_ID();
			}

			if ( get_transient('wps_product_single_all_variants_' . $post_id) ) {
				return get_transient('wps_product_single_all_variants_' . $post_id);
			}

			$query = $this->get_variants_from_post_id_query();
			$query_prepared = $wpdb->prepare($query, $post_id);

			$variants = $wpdb->get_results($query_prepared);

			set_transient('wps_product_single_all_variants_' . $post_id, $variants);

			return $variants;

		}


		/*

		Get Product Variants

		Note: only gets variants that are in stock, perhaps we change

		*/
		public function get_variants_from_post_id_query() {
			return "SELECT variants.* FROM " . WPS_TABLE_NAME_PRODUCTS . " as products INNER JOIN " . WPS_TABLE_NAME_VARIANTS . " as variants ON products.product_id = variants.product_id WHERE products.post_id = %d";
		}


		/*

		Responsible for getting the variants amount

		*/
		public function get_variants_amount($variants) {
			return count($variants);
		}


		/*

		Responsible for sorting by price

		*/
		public function sort_by_price($item_a, $item_b) {
			return $item_a->price > $item_b->price;
		}


		/*

		Responsible for sorting variants by price

		*/
		public function sort_variants_by_price($variants) {

			usort($variants, [__CLASS__, 'sort_by_price']);

			return $variants;

		}


		/*

		Responsible for retrieving the first variant price in a list of product variants

		*/
		public function get_first_variant_price($variants) {
			return $variants[0]->price;
		}


		/*

		Responsible for checking if all variant prices match

		*/
		public function check_if_all_variant_prices_match($last_variant_price, $first_variant_price) {
			return $last_variant_price === $first_variant_price;
		}


		/*

		Responsible for getting the last variant index

		*/
		public function get_last_variant_index($variants_amount) {
			return $variants_amount - 1;
		}


		/*

		Responsible for getting the last variant price

		*/
		public function get_last_variant_price($variants, $last_variant_index) {
			return $variants[$last_variant_index]->price;
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
        variant_id bigint(100) unsigned NOT NULL DEFAULT 0,
        product_id bigint(100) DEFAULT NULL,
        image_id bigint(100) DEFAULT NULL,
        title varchar(255) DEFAULT NULL,
        price decimal(12,2) DEFAULT 0,
        compare_at_price decimal(12,2) DEFAULT 0,
        position int(20) DEFAULT NULL,
        option1 varchar(100) DEFAULT NULL,
        option2 varchar(100) DEFAULT NULL,
        option3 varchar(100) DEFAULT NULL,
        taxable tinyint(1) DEFAULT NULL,
        sku varchar(255) DEFAULT NULL,
        inventory_policy varchar(255) DEFAULT NULL,
        inventory_quantity bigint(20) DEFAULT NULL,
        old_inventory_quantity bigint(20) DEFAULT NULL,
        inventory_management varchar(255) DEFAULT NULL,
        fulfillment_service varchar(255) DEFAULT NULL,
        barcode varchar(255) DEFAULT NULL,
        weight int(20) DEFAULT NULL,
        weight_unit varchar(100) DEFAULT NULL,
        requires_shipping tinyint(1) DEFAULT NULL,
        created_at datetime,
        updated_at datetime,
				admin_graphql_api_id longtext DEFAULT NULL,
        PRIMARY KEY  (id)
      ) ENGINE=InnoDB $collate";

  	}


  }

}
