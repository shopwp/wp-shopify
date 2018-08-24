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


  	public function __construct() {

			$this->table_name         				= WPS_TABLE_NAME_VARIANTS;
      $this->primary_key        				= 'id';
      $this->version            				= '1.0';
      $this->cache_group        				= 'wps_db_variants';

    }


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



		public function insert_variant($variant) {
			return $this->insert( $this->rename_primary_key($variant, 'variant_id'), 'variant');
		}


		public function insert_variants($variants) {

			$results = [];

			foreach ($variants as $variant) {

				$insertion_result = $this->insert_variant($variant);

				if (is_wp_error($insertion_result)) {
					return $insertion_result;
				}

				$results[] = $insertion_result;

			}

			return $results;

		}


    /*

    Insert variant

    */
    public function insert_variants_from_product($product) {

      $results = [];
			$product = Utils::convert_array_to_object($product);

      if (isset($product->variants) && $product->variants) {
				return $this->insert_variants($product->variants);
      }

      return $results;

    }


    /*

    Get single shop info value
		Returns (array) of insertion results or WP_Error

    */
  	public function insert_variants_from_products($products) {

      $results = [];

			$products = Utils::wrap_in_array($products);

      foreach ($products as $key => $product) {

				$insertion_result = $this->insert_variants_from_product($product);

				if (is_wp_error($insertion_result)) {
					return $insertion_result;
				}

				$results[] = $insertion_result;

      }

      return $results;

    }


		/*

		Get Product Variants

		*/
		public function get_variants_from_post_id($postID = null) {

			global $wpdb;

			if ($postID === null) {
				$postID = get_the_ID();
			}

			if (get_transient('wps_product_single_variants_' . $postID)) {
				$variants = get_transient('wps_product_single_variants_' . $postID);

			} else {

				$query = "SELECT variants.* FROM " . WPS_TABLE_NAME_PRODUCTS . " as products INNER JOIN " . WPS_TABLE_NAME_VARIANTS . " as variants ON products.product_id = variants.product_id WHERE products.post_id = %d";

				$variants = $wpdb->get_results( $wpdb->prepare($query, $postID) );

				$variants = Utils::product_inventory(false, $variants);

				set_transient('wps_product_single_variants_' . $postID, $variants);

			}

			return $variants;

		}


    /*

    Update variant from product

		In order to handle an update being initated by _new_ data (e.g., when a new variant is added),
		we need to compare what's currently in the database with what gets sent back via the
		product/update webhook.

    */
  	public function update_variants_from_product($product) {

      $results = [];
      $variantsFromShopify = $product->variants;

      $currentVariants = $this->get_rows('product_id', $product->id);
      $currentVariantsArray = Utils::convert_object_to_array($currentVariants);
      $variantsFromShopify = Utils::convert_object_to_array($variantsFromShopify);

      $variantsToAdd = Utils::wps_find_items_to_add($currentVariantsArray, $variantsFromShopify, true);
      $variantsToDelete = Utils::wps_find_items_to_delete($currentVariantsArray, $variantsFromShopify, true);


      if (count($variantsToAdd) > 0) {

        foreach ($variantsToAdd as $key => $newVariant) {
          $results['created'][] = $this->insert($newVariant, 'variant');
        }

      }


      if (count($variantsToDelete) > 0) {

        foreach ($variantsToDelete as $key => $oldVariant) {

          if (is_array($oldVariant) && isset($oldVariant['variant_id'])) {
            $results['deleted'][] = $this->delete($oldVariant['variant_id']);
          }

        }

      }


      foreach ($product->variants as $key => $variant) {
        $results['updated'] = $this->update($variant->id, $variant);
    	}


      return $results;

    }



		/*

	  Delete variants from product ID

	  */
	  public function delete_variants_from_product_id($product_id) {
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
