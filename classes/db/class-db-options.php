<?php

namespace WPS\DB;

use WPS\Utils;
use WPS\DB\Products;

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
	public function insert_options($products) {

    $results = array();

    foreach ($products as $key => $product) {

      if (isset($product->options) && $product->options) {
        foreach ($product->options as $key => $option) {
          $results[] = $this->insert($option, 'option');
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
    $optionsFromShopify = $product->options;

    /*

    In order to handle image creation / deletions, we need to compare what's
    currently in the database with what gets sent back via the
    product/update webhook.

    */
    $currentOptions = $this->get_rows('product_id', $product->id);
    // $currentOptionsArray = Utils::wps_convert_object_to_array($currentOptions);
    // $optionsFromShopify = Utils::wps_convert_object_to_array($optionsFromShopify);

    $optionsToAdd = Utils::wps_find_items_to_add($currentOptions, $optionsFromShopify, true);
    $optionsToDelete = Utils::wps_find_items_to_delete($currentOptions, $optionsFromShopify, true);


    if (count($optionsToAdd) > 0) {
      foreach ($optionsToAdd as $key => $newOption) {
        $results['created'][] = $this->insert($newOption, 'option');
      }

    } else {
      // error_log('------ No new options to create -------');
    }


    if (count($optionsToDelete) > 0) {
      foreach ($optionsToDelete as $key => $oldOption) {
        $results['deleted'][] = $this->delete($oldOption->id);
      }

    } else {
      // error_log('------ No new options to delete -------');
    }


    foreach ($product->options as $key => $option) {
      $results['updated'] = $this->update($option->id, $option);
    }

    // error_log('@@@@@@@@@ Final Updated Options @@@@@@@@@');
    // error_log(print_r($results, true));
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

    $DB_Products = new Products();
    $table_products = $DB_Products->get_table_name();

    if ($postID === null) {
      $postID = get_the_ID();
    }

    $query = "SELECT options.* FROM $table_products as products INNER JOIN $this->table_name as options ON products.product_id = options.product_id WHERE products.post_id = %d";

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
      `id` bigint(100) unsigned NOT NULL,
      `product_id` bigint(100) DEFAULT NULL,
      `name` varchar(100) DEFAULT NULL,
      `position` int(20) DEFAULT NULL,
      `values` longtext DEFAULT NULL,
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
