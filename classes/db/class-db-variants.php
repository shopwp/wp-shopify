<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\DB\Products;

class Variants extends \WPS\DB {

  public $table_name;
	public $version;
	public $primary_key;

  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name         = $wpdb->prefix . 'wps_variants';
    $this->primary_key        = 'id';
    $this->version            = '1.0';
    $this->cache_group        = 'wps_db_variants';

  }


  /*

  Get Columns

  */
	public function get_columns() {
    return array(
      'id'                        => '%d',
      'product_id'                => '%d',
      'image_id'                  => '%d',
      'title'                     => '%s',
      'price'                     => '%s',
      'compare_at_price'          => '%d',
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
      'updated_at'                => '%s'
    );
  }


  /*

  Get Column Defaults

  */
	public function get_column_defaults() {
    return array(
      'id'                        => '',
      'product_id'                => '',
      'image_id'                  => '',
      'title'                     => '',
      'price'                     => '',
      'compare_at_price'          => '',
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
      'created_at'                => date( 'Y-m-d H:i:s' ),
      'updated_at'                => date( 'Y-m-d H:i:s' )
    );
  }


  /*

  Get single shop info value

  */
	public function insert_variants($products) {

    $results = array();

    foreach ($products as $key => $product) {

      if (isset($product->variants) && $product->variants) {

        foreach ($product->variants as $key => $variant) {
          $results[] = $this->insert($variant, 'variant');
        }
      }

    }

    return $results;

  }


  /*

  update_variant

  */
	public function update_variant($product) {

    $results = array();
    $variantsFromShopify = $product->variants;

    /*

    In order to handle image creation / deletions, we need to compare what's
    currently in the database with what gets sent back via the
    product/update webhook.

    */
    $currentVariants = $this->get_rows('product_id', $product->id);
    $currentVariantsArray = Utils::wps_convert_object_to_array($currentVariants);
    $variantsToAdd = Utils::wps_find_items_to_add($currentVariantsArray, $variantsFromShopify);
    $variantsToDelete = Utils::wps_find_items_to_delete($currentVariantsArray, $variantsFromShopify);


    // error_log('!!!!!!! $variantsToAdd !!!!!!!!');
    // error_log(print_r($variantsToAdd, true));
    //
    //
    // error_log('!!!!!!! $variantsToDelete !!!!!!!!');
    // error_log(print_r($variantsToDelete, true));


    if (count($variantsToAdd) > 0) {
      foreach ($variantsToAdd as $key => $newVariant) {
        $results['created'][] = $this->insert($newVariant, 'variant');
      }

    } else {
      // error_log('------ No new variants to create -------');
    }


    if (count($variantsToDelete) > 0) {
      foreach ($variantsToDelete as $key => $oldVariant) {
        $results['deleted'][] = $this->delete($oldVariant->id);
      }

    } else {
      // error_log('------ No new variants to delete -------');
    }


    foreach ($product->variants as $key => $variant) {
      $results['updated'] = $this->update($variant->id, $variant);
    }

    // error_log('@@@@@@@@@ Final Updated Variants @@@@@@@@@');
    // error_log(print_r($results, true));
    return $results;


  }


  /*

  Get Product Variants

  */
  public function get_product_variants($postID = null) {

    global $wpdb;

    $DB_Products = new Products();
    $table_products = $DB_Products->get_table_name();

    if ($postID === null) {
      $postID = get_the_ID();
    }

    $query = "SELECT variants.* FROM $table_products as products INNER JOIN $this->table_name as variants ON products.product_id = variants.product_id WHERE products.post_id = %d";

    return $wpdb->get_results(
      $wpdb->prepare($query, $postID)
    );

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
      `id` bigint(100) unsigned NOT NULL AUTO_INCREMENT,
      `product_id` bigint(100) DEFAULT NULL,
      `image_id` bigint(100) DEFAULT NULL,
      `title` varchar(255) DEFAULT NULL,
      `price` varchar(100) DEFAULT NULL,
      `compare_at_price` varchar(100) DEFAULT NULL,
      `position` int(20) DEFAULT NULL,
      `option1` varchar(100) DEFAULT NULL,
      `option2` varchar(100) DEFAULT NULL,
      `option3` varchar(100) DEFAULT NULL,
      `taxable` tinyint(1) DEFAULT NULL,
      `sku` varchar(255) DEFAULT NULL,
      `inventory_policy` varchar(255) DEFAULT NULL,
      `inventory_quantity` bigint(20) DEFAULT NULL,
      `old_inventory_quantity` bigint(20) DEFAULT NULL,
      `inventory_management` varchar(255) DEFAULT NULL,
      `fulfillment_service` varchar(255) DEFAULT NULL,
      `barcode` varchar(255) DEFAULT NULL,
      `weight` int(20) DEFAULT NULL,
      `weight_unit` varchar(100) DEFAULT NULL,
      `requires_shipping` tinyint(1) DEFAULT NULL,
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
