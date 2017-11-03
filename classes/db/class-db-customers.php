<?php

namespace WPS\DB;

use WPS\Config;
use WPS\WS;
use WPS\Utils;


class Customers extends \WPS\DB {

  public $table_name;
  public $version;
  public $primary_key;

  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name         = $wpdb->prefix . 'wps_customers';
    $this->primary_key        = 'id';
    $this->version            = '1.0';
    $this->cache_group        = 'wps_db_customers';

  }


  /*

  Get Single Customer

  */
  public function get_customer($customerID = null) {

    global $wpdb;

    if ($customerID === null) {
      $customerID = get_the_ID();
    }

    if (get_transient('wps_customer_single_' . $customerID)) {
      $results = get_transient('wps_customer_single_' . $customerID);

    } else {

      $query = "SELECT customers.* FROM $this->table_name as customers WHERE customers.post_id = %d";
      $results = $wpdb->get_row( $wpdb->prepare($query, $customerID) );

      set_transient('wps_customer_single_' . $customerID, $results);

    }

    return $results;

  }


  /*

  Get Customers

  */
  public function get_customers() {
    return $this->get_all_rows();
  }


  /*

  Insert customers

  */
  public function insert_customers($customers) {

    $DB_Settings_Connection = new Settings_Connection();
    $results = array();
    $index = 1;

    foreach ($customers as $key => $customer) {

      if ($DB_Settings_Connection->is_syncing() || $DB_Settings_Connection->is_webhooking()) {

        // If product is visible on the Online Stores channel
        if (property_exists($customer, 'created_at') && $customer->created_at !== null) {

          // Converting to a fully qualified associative array
          $customer = json_decode(json_encode($customer), true);

          $results[] = $this->insert($customer, 'customer');

        }

      } else {

        $results = false;
        break;

      }

      $index++;

    }

    return $results;

  }


  /*

  Fired when product is update at Shopify

  */
  public function update_customer($customer) {

    /*

    If published_at is null, we know the user turned off the Online Store sales channel.
    TODO: Shopify may implement better sales channel checking in the future API. We should
    then check for Buy Button visibility as-well.

    */
    if (property_exists($customer, 'published_at') && $customer->published_at !== null) {

      $DB_Variants = new Variants();
      $DB_Options = new Options();
      $DB_Images = new Images();
      $DB_Collects = new Collects();
      $DB_Tags = new Tags();

      /*

      TODO: Move to a Util
      Needed to update 'image' col in products table. Object is returned
      Shopify so need to only save image URL. Rest of images live in
      images table_name

      */
      if (property_exists($customer, 'image') && !empty($customer->image)) {
        $customer->image = $customer->image->src;
      }

      $results['variants']    = $DB_Variants->update_variant($customer);
      $results['options']     = $DB_Options->update_option($customer);
      $results['product']     = $this->update($customer->id, $customer);
      $results['image']       = $DB_Images->update_image($customer);
      $results['collects']    = $DB_Collects->update_collects($customer);

      // This takes care of syncing the custom post type content
      $results['product_cpt'] = CPT::wps_update_existing_product($customer);

      $results['tags']        = $DB_Tags->update_tags($customer, $results['product_cpt']);


    } else {
      $results['deleted_product'] = $this->delete_product($customer, $customer->id);

    }

    Transients::delete_cached_prices();
    Transients::delete_cached_variants();
    Transients::delete_cached_product_single();
    Transients::delete_cached_product_queries();

    return $results;

  }


  /*

  Fired when customer is deleted at Shopify

  */
  public function delete_customer($customer, $customerID = null) {

    $Backend = new Backend(new Config());

    if ($customerID === null) {
      $customerID = $customer->id;
    }

    $customerData = $this->get($customerID);

    $results['customers'] = $this->delete($customerID);

    return $results;

  }


  /*

  Fired when customer is created at Shopify

  */
  public function create_customer($customer) {

    $customerWrapped = array();
    $customerWrapped[] = $customer;
    $results = array();

    $results['customers'] = $this->insert_customers($customerWrapped);

    return $results;

  }


  /*

  Update customers

  */
  public function update_customers($customers) {

    $result = array();

    foreach ($customers as $key => $customer) {
      $result[] = $this->update($customer['id'], $customer);
    }

    return $result;

  }


  /*

  Rename primary key

  */
  public function rename_primary_key($customer) {

    $customerCopy = $customer;
    $customerCopy->customer_id = $customerCopy->id;
    unset($customerCopy->id);

    return $customerCopy;

  }


  /*

  Get Columns

  */
	public function get_columns() {

    return array(
      'id'                        => '%d',
      'email'                     => '%s',
      'accepts_marketing'         => '%d',
      'created_at'                => '%s',
      'updated_at'                => '%s',
      'first_name'                => '%s',
      'last_name'                 => '%s',
      'orders_count'              => '%d',
      'state'                     => '%s',
      'total_spent'               => '%s',
      'last_order_id'             => '%d',
      'note'                      => '%s',
      'verified_email'            => '%d',
      'multipass_identifier'      => '%s',
      'tax_exempt'                => '%d',
      'phone'                     => '%s',
      'tags'                      => '%s',
      'last_order_name'           => '%s',
      'default_address'           => '%s',
      'addresses'                 => '%s'
    );

  }


  /*

  Get Column Defaults

  */
	public function get_column_defaults() {

    return array(
      'id'                        => 0,
      'email'                     => '',
      'accepts_marketing'         => 0,
      'created_at'                => '',
      'updated_at'                => '',
      'first_name'                => '',
      'last_name'                 => '',
      'orders_count'              => 0,
      'state'                     => '',
      'total_spent'               => '',
      'last_order_id'             => 0,
      'note'                      => '',
      'verified_email'            => 0,
      'multipass_identifier'      => '',
      'tax_exempt'                => 0,
      'phone'                     => '',
      'tags'                      => '',
      'last_order_name'           => '',
      'default_address'           => '',
      'addresses'                 => ''
    );

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
      `id` bigint(100) unsigned NOT NULL,
      `email` varchar(255) DEFAULT NULL,
      `accepts_marketing` tinyint(1) DEFAULT 0,
      `created_at` datetime,
      `updated_at` datetime,
      `first_name` varchar(255) DEFAULT NULL,
      `last_name` varchar(255) DEFAULT NULL,
      `orders_count` tinyint(1) DEFAULT 0,
      `state` varchar(255) DEFAULT NULL,
      `total_spent` varchar(255) DEFAULT NULL,
      `last_order_id` bigint(100) unsigned DEFAULT NULL,
      `note` mediumtext,
      `verified_email` tinyint(1) DEFAULT 0,
      `multipass_identifier` mediumtext,
      `tax_exempt` tinyint(1) DEFAULT 0,
      `phone` varchar(255) DEFAULT NULL,
      `tags` mediumtext,
      `last_order_name` mediumtext,
      `default_address` mediumtext,
      `addresses` mediumtext,
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
