<?php

namespace WPS\DB;

use WPS\Config;
use WPS\WS;
use WPS\Backend;
use WPS\Utils;
use WPS\Progress_Bar;


// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	exit;
}


/*

Database class for Customers

*/
if (!class_exists('Customers')) {

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
      $progress = new Progress_Bar(new Config());
      $index = 1;


      if (is_array($customers)) {

        foreach ($customers as $key => $customer) {

          if (!Utils::isStillSyncing()) {
            wp_die();
      		}

          // If product is visible on the Online Stores channel
          if (property_exists($customer, 'created_at') && $customer->created_at !== null) {

            // Converting to a fully qualified associative array
            $customer = json_decode(json_encode($customer), true);

            $results[] = $this->insert($customer, 'customer');
            $progress->increment_current_amount('customers');

          }

          $index++;

        }

       } else if (is_object($customers)) {

        $customers = json_decode(json_encode($customers), true);
        $results[] = $this->insert($customers, 'customer');

      }

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

      if (is_array($customers)) {

        foreach ($customers as $key => $customer) {
          $result[] = $this->update($customer['id'], $customer);
        }

      } else if (is_object($customers)) {

        $customers = json_decode(json_encode($customers), true);
        $result[] = $this->update($customers['id'], $customers);

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
        `note` longtext DEFAULT NULL,
        `verified_email` tinyint(1) DEFAULT 0,
        `multipass_identifier` longtext DEFAULT NULL,
        `tax_exempt` tinyint(1) DEFAULT 0,
        `phone` varchar(255) DEFAULT NULL,
        `tags` longtext DEFAULT NULL,
        `last_order_name` longtext DEFAULT NULL,
        `default_address` longtext DEFAULT NULL,
        `addresses` longtext DEFAULT NULL,
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

}
