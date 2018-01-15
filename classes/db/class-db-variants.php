<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\DB\Products;
use WPS\DB\Settings_Connection;
use WPS\Progress_Bar;
use WPS\Config;

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
      'created_at'                => date_i18n( 'Y-m-d H:i:s' ),
      'updated_at'                => date_i18n( 'Y-m-d H:i:s' )
    );
  }









  public function insert_variant($product) {

    $results = [];
    $DB_Settings_Connection = new Settings_Connection();

    if (isset($product->variants) && $product->variants) {

      foreach ($product->variants as $key => $variant) {

        if (!Utils::isStillSyncing()) {
          wp_die();
          break;
        }

        return $results[] = $this->insert($variant, 'variant');

      }

    }

  }







  /*

  Get single shop info value

  */
	public function insert_variants($products) {

    $DB_Settings_Connection = new Settings_Connection();
    $progress = new Progress_Bar(new Config());
    $results = array();

    foreach ($products as $key => $product) {

      if (isset($product->variants) && $product->variants) {

        foreach ($product->variants as $key => $variant) {

          if (!Utils::isStillSyncing()) {
            wp_die();
            break 2;
          }

          $results[] = $this->insert($variant, 'variant');
          $progress->increment_current_amount('products');

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
    $newProductID = Utils::wps_find_product_id($product);

    /*

    In order to handle an update being initated by _new_ data (e.g., when a new variant is added),
    we need to compare what's currently in the database with what gets sent back via the
    product/update webhook.

    */
    $currentVariants = $this->get_rows('product_id', $newProductID);
    $currentVariantsArray = Utils::wps_convert_object_to_array($currentVariants);
    $variantsFromShopify = Utils::wps_convert_object_to_array($variantsFromShopify);

    $variantsToAdd = Utils::wps_find_items_to_add($currentVariantsArray, $variantsFromShopify, true);
    $variantsToDelete = Utils::wps_find_items_to_delete($currentVariantsArray, $variantsFromShopify, true);


    if (count($variantsToAdd) > 0) {

      foreach ($variantsToAdd as $key => $newVariant) {
        $results['created'][] = $this->insert($newVariant, 'variant');
      }

    }


    if (count($variantsToDelete) > 0) {

      foreach ($variantsToDelete as $key => $oldVariant) {

        if (is_array($oldVariant) && isset($oldVariant['id'])) {
          $results['deleted'][] = $this->delete($oldVariant['id']);
        }

      }

    }

    foreach ($product->variants as $key => $variant) {

      $results['updated'] = $this->update($variant->id, $variant);

    }

    return $results;

  }


  /*

  Get Product Variants

  */
  public function get_product_variants($postID = null) {

    global $wpdb;

    if ($postID === null) {
      $postID = get_the_ID();
    }

    if (get_transient('wps_product_single_variants_' . $postID)) {
      $results = get_transient('wps_product_single_variants_' . $postID);

    } else {

      $DB_Products = new Products();
      $table_products = $DB_Products->get_table_name();

      $query = "SELECT variants.* FROM $table_products as products INNER JOIN $this->table_name as variants ON products.product_id = variants.product_id WHERE products.post_id = %d";

      $results = $wpdb->get_results( $wpdb->prepare($query, $postID) );

      set_transient('wps_product_single_variants_' . $postID, $results);

    }

    return $results;

  }


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
