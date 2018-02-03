<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\DB\Products;
use WPS\DB\Settings_Connection;
use WPS\Progress_Bar;
use WPS\Config;

class Options extends \WPS\DB {

  public $table_name;
	public $version;
	public $primary_key;

  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name         = $wpdb->prefix . 'wps_options';
    $this->primary_key        = 'id';
    $this->version            = '1.0';
    $this->cache_group        = 'wps_db_options';

  }


  /*

  Get Columns

  */
	public function get_columns() {
    return array(
      'id'                        => '%d',
      'product_id'                => '%d',
      'name'                      => '%s',
      'position'                  => '%d',
      'values'                    => '%s'
    );
  }


  /*

  Get Column Defaults

  */
	public function get_column_defaults() {
    return array(
      'id'                        => 0,
      'product_id'                => 0,
      'name'                      => '',
      'position'                  => 0,
      'values'                    => ''
    );
  }














  /*

  Get single shop info value

  */
	public function insert_option($product) {

    $DB_Settings_Connection = new Settings_Connection();
    $results = [];


    if (isset($product->options) && $product->options) {

      foreach ($product->options as $key => $option) {

        if (!Utils::isStillSyncing()) {
          wp_die();
          break;
        }

        $results[] = $this->insert($option, 'option');

      }

      return $results;

    }

  }










  /*

  Get single shop info value

  */
	public function insert_options($products) {

    $DB_Settings_Connection = new Settings_Connection();
    $progress = new Progress_Bar(new Config());
    $results = array();

    foreach ($products as $key => $product) {

      if (isset($product->options) && $product->options) {

        foreach ($product->options as $key => $option) {

          if (!Utils::isStillSyncing()) {
            wp_die();
            break 2;
          }

          $results[] = $this->insert($option, 'option');
          $progress->increment_current_amount('products');

        }

      }

    }

    return $results;

  }


  /*

  update_option

  */
	public function update_option($product) {


    $results = array();
    $Products = new Products();
    $optionsFromShopify = $product->options;

    $newProductID = Utils::wps_find_product_id($product);
    $currentOptions = $this->get_rows('product_id', $newProductID);

    // If the product doesn't exist, insert it instead
    if (is_array($currentOptions) && empty($currentOptions)) {

      $results = $Products->create_product($product);

    } else {

      // $currentOptionsArray = Utils::wps_convert_object_to_array($currentOptions);
      // $optionsFromShopify = Utils::wps_convert_object_to_array($optionsFromShopify);

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

    }

    return $results;

  }


  /*

  update_option

  */
	public function delete_option($product) {

    $results = array();

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

  Get Product Variants

  */
  public function get_product_options($postID = null) {

    global $wpdb;

    if ($postID === null) {
      $postID = get_the_ID();
    }

    if (get_transient('wps_product_single_options_' . $postID)) {
      $results = get_transient('wps_product_single_options_' . $postID);

    } else {

      $DB_Products = new Products();
      $table_products = $DB_Products->get_table_name();

      $query = "SELECT options.* FROM $table_products as products INNER JOIN $this->table_name as options ON products.product_id = options.product_id WHERE products.post_id = %d";

      $results = $wpdb->get_results( $wpdb->prepare($query, $postID) );

      set_transient('wps_product_single_options_' . $postID, $results);

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
      `name` varchar(100) DEFAULT NULL,
      `position` int(20) DEFAULT NULL,
      `values` longtext DEFAULT NULL,
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
