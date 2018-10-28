<?php

namespace WPS\DB;

use WPS\Utils;

if (!defined('ABSPATH')) {
	exit;
}


class Variants extends \WPS\DB {

	public $table_name_suffix;
	public $table_name;
	public $version;
	public $primary_key;
	public $lookup_key;
	public $cache_group;
	public $type;

	public $default_id;
	public $default_variant_id;
	public $default_product_id;
	public $default_image_id;
	public $default_title;
	public $default_price;
	public $default_compare_at_price;
	public $default_position;
	public $default_option1;
	public $default_option2;
	public $default_option3;
	public $default_option_values;
	public $default_taxable;
	public $default_weight;
	public $default_weight_unit;
	public $default_sku;
	public $default_inventory_policy;
	public $default_inventory_quantity;
	public $default_old_inventory_quantity;
	public $default_inventory_management;
	public $default_requires_shipping;
	public $default_fulfillment_service;
	public $default_barcode;
	public $default_created_at;
	public $default_updated_at;
	public $default_admin_graphql_api_id;


	public function __construct() {

		// Table info
		$this->table_name_suffix    							= WPS_TABLE_NAME_VARIANTS;
		$this->table_name         								= $this->get_table_name();
		$this->version            								= '1.0';
		$this->primary_key        								= 'id';
		$this->lookup_key        									= 'variant_id';
		$this->cache_group        								= 'wps_db_variants';
		$this->type        												= 'variant';

		// Defaults
		$this->default_id 												= 0;
		$this->default_variant_id                	= 0;
		$this->default_product_id                	= 0;
		$this->default_image_id                  	= 0;
		$this->default_title                     	= '';
		$this->default_price                     	= 0;
		$this->default_compare_at_price          	= 0;
		$this->default_position                  	= 0;
		$this->default_option1                   	= '';
		$this->default_option2                   	= '';
		$this->default_option3                   	= '';
		$this->default_option_values             	= '';
		$this->default_taxable                   	= 0;
		$this->default_weight                    	= 0;
		$this->default_weight_unit               	= '';
		$this->default_sku                       	= '';
		$this->default_inventory_policy          	= '';
		$this->default_inventory_quantity        	= 0;
		$this->default_old_inventory_quantity    	= 0;
		$this->default_inventory_management      	= '';
		$this->default_requires_shipping         	= 0;
		$this->default_fulfillment_service       	= '';
		$this->default_barcode                   	= '';
		$this->default_created_at                	= date_i18n( 'Y-m-d H:i:s' );
		$this->default_updated_at                	= date_i18n( 'Y-m-d H:i:s' );
		$this->default_admin_graphql_api_id				= '';



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
			'option_values'             => '%s',
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
			'id'                        => $this->default_id,
			'variant_id'                => $this->default_variant_id,
			'product_id'                => $this->default_product_id,
			'image_id'                  => $this->default_image_id,
			'title'                     => $this->default_title,
			'price'                     => $this->default_price,
			'compare_at_price'          => $this->default_compare_at_price,
			'position'                  => $this->default_position,
			'option1'                   => $this->default_option1,
			'option2'                   => $this->default_option2,
			'option3'                   => $this->default_option3,
			'option_values'             => $this->default_option_values,
			'taxable'                   => $this->default_taxable,
			'weight'                    => $this->default_weight,
			'weight_unit'               => $this->default_weight_unit,
			'sku'                       => $this->default_sku,
			'inventory_policy'          => $this->default_inventory_policy,
			'inventory_quantity'        => $this->default_inventory_quantity,
			'old_inventory_quantity'    => $this->default_old_inventory_quantity,
			'inventory_management'      => $this->default_inventory_management,
			'requires_shipping'         => $this->default_requires_shipping,
			'fulfillment_service'       => $this->default_fulfillment_service,
			'barcode'                   => $this->default_barcode,
			'created_at'                => $this->default_created_at,
			'updated_at'                => $this->default_updated_at,
			'admin_graphql_api_id'			=> $this->default_admin_graphql_api_id
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

		global $wpdb;

		return "SELECT variants.* FROM " . $wpdb->prefix . WPS_TABLE_NAME_PRODUCTS . " as products INNER JOIN " . $this->table_name . " as variants ON products.product_id = variants.product_id WHERE products.post_id = %d";

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

		if ( empty($variants) ) {
			return false;
		}

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

		if ($last_variant_index < 0) {
			return false;
		}

		return $variants[$last_variant_index]->price;

	}



























	public function add_product_id_to_variants($variants, $product) {

		foreach ($variants as $variant) {

			if ( !Utils::has($variant, 'product_id') ) {

				if (Utils::has($product, 'product_id')) {
					$variant->product_id = $product->product_id;

				} else if (Utils::has($product, 'id')) {
					$variant->product_id = $product->id;
				}

			}

		}

		return $variants;

	}


	/*

	Maybe renames primary key of data before update / insert

	*/
	public function maybe_add_product_id_to_variants($maybe_products) {

		if ( is_array($maybe_products) ) {

			foreach ($maybe_products as $product) {
				$product->variants = $this->add_product_id_to_variants($product->variants, $product);
			}

		} else {
			$maybe_products->variants = $this->add_product_id_to_variants($maybe_products->variants, $maybe_products);
		}

		return $maybe_products;

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
			variant_id bigint(100) unsigned NOT NULL DEFAULT '{$this->default_variant_id}',
			product_id bigint(100) DEFAULT '{$this->default_product_id}',
			image_id bigint(100) DEFAULT '{$this->default_image_id}',
			title varchar(255) DEFAULT '{$this->default_title}',
			price decimal(12,2) DEFAULT '{$this->default_price}',
			compare_at_price decimal(12,2) DEFAULT '{$this->default_compare_at_price}',
			position int(20) DEFAULT '{$this->default_position}',
			option1 varchar(100) DEFAULT '{$this->default_option1}',
			option2 varchar(100) DEFAULT '{$this->default_option2}',
			option3 varchar(100) DEFAULT '{$this->default_option3}',
			option_values longtext DEFAULT '{$this->default_option_values}',
			taxable tinyint(1) DEFAULT '{$this->default_taxable}',
			sku varchar(255) DEFAULT '{$this->default_sku}',
			inventory_policy varchar(255) DEFAULT '{$this->default_inventory_policy}',
			inventory_quantity bigint(20) DEFAULT '{$this->default_inventory_quantity}',
			old_inventory_quantity bigint(20) DEFAULT '{$this->default_old_inventory_quantity}',
			inventory_management varchar(255) DEFAULT '{$this->default_inventory_management}',
			fulfillment_service varchar(255) DEFAULT '{$this->default_fulfillment_service}',
			barcode varchar(255) DEFAULT '{$this->default_barcode}',
			weight int(20) DEFAULT '{$this->default_weight}',
			weight_unit varchar(100) DEFAULT '{$this->default_weight_unit}',
			requires_shipping tinyint(1) DEFAULT '{$this->default_requires_shipping}',
			created_at datetime DEFAULT '{$this->default_created_at}',
			updated_at datetime DEFAULT '{$this->default_updated_at}',
			admin_graphql_api_id longtext DEFAULT '{$this->default_admin_graphql_api_id}',
			PRIMARY KEY  (id)
		) ENGINE=InnoDB $collate";

	}


}
