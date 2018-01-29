<?php

namespace WPS\DB;

use WPS\Config;
use WPS\WS;
use WPS\Utils;
use WPS\Progress_Bar;

class Orders extends \WPS\DB {

  public $table_name;
  public $version;
  public $primary_key;


  /*

  Construct

  */
	public function __construct() {

    global $wpdb;
    $this->table_name         = $wpdb->prefix . 'wps_orders';
    $this->primary_key        = 'id';
    $this->version            = '1.0';
    $this->cache_group        = 'wps_db_orders';

  }


  /*

  Get Columns

  */
  public function get_columns() {

    return array(
      'id'                        => '%d',
      'customer_id'               => '%d',
      'email'                     => '%s',
      'closed_at'                 => '%s',
      'created_at'                => '%s',
      'updated_at'                => '%s',
      'number'                    => '%d',
      'note'                      => '%s',
      'token'                     => '%s',
      'gateway'                   => '%s',
      'test'                      => '%s',
      'total_price'               => '%s',
      'subtotal_price'            => '%s',
      'total_weight'              => '%d',
      'total_tax'                 => '%s',
      'taxes_included'            => '%d',
      'currency'                  => '%s',
      'financial_status'          => '%s',
      'confirmed'                 => '%d',
      'total_discounts'           => '%s',
      'total_line_items_price'    => '%s',
      'cart_token'                => '%s',
      'buyer_accepts_marketing'   => '%d',
      'name'                      => '%s',
      'referring_site'            => '%s',
      'landing_site'              => '%s',
      'cancelled_at'              => '%s',
      'cancel_reason'             => '%s',
      'total_price_usd'           => '%s',
      'checkout_token'            => '%s',
      'reference'                 => '%s',
      'user_id'                   => '%s',
      'location_id'               => '%s',
      'source_identifier'         => '%s',
      'source_url'                => '%s',
      'processed_at'              => '%s',
      'device_id'                 => '%s',
      'phone'                     => '%s',
      'customer_locale'           => '%s',
      'app_id'                    => '%d',
      'browser_ip'                => '%s',
      'landing_site_ref'          => '%s',
      'order_number'              => '%d',
      'discount_codes'            => '%s',
      'note_attributes'           => '%s',
      'payment_gateway_names'     => '%s',
      'processing_method'         => '%s',
      'checkout_id'               => '%d',
      'source_name'               => '%s',
      'fulfillment_status'        => '%s',
      'tax_lines'                 => '%s',
      'tags'                      => '%s',
      'contact_email'             => '%s',
      'order_status_url'          => '%s',
      'line_items'                => '%s',
      'shipping_lines'            => '%s',
      'billing_address'           => '%s',
      'shipping_address'          => '%s',
      'fulfillments'              => '%s',
      'client_details'            => '%s',
      'refunds'                   => '%s',
      'customer'                  => '%s'
    );

  }


  /*

  Get Column Defaults

  */
  public function get_column_defaults() {

    return array(
      'id'                        => 0,
      'customer_id'               => 0,
      'email'                     => '',
      'closed_at'                 => date_i18n( 'Y-m-d H:i:s' ),
      'created_at'                => date_i18n( 'Y-m-d H:i:s' ),
      'updated_at'                => date_i18n( 'Y-m-d H:i:s' ),
      'number'                    => 0,
      'note'                      => '',
      'token'                     => '',
      'gateway'                   => '',
      'test'                      => '',
      'total_price'               => '',
      'subtotal_price'            => '',
      'total_weight'              => 0,
      'total_tax'                 => '',
      'taxes_included'            => 0,
      'currency'                  => '',
      'financial_status'          => '',
      'confirmed'                 => 0,
      'total_discounts'           => '',
      'total_line_items_price'    => '',
      'cart_token'                => '',
      'buyer_accepts_marketing'   => 0,
      'name'                      => '',
      'referring_site'            => '',
      'landing_site'              => '',
      'cancelled_at'              => date_i18n( 'Y-m-d H:i:s' ),
      'cancel_reason'             => '',
      'total_price_usd'           => '',
      'checkout_token'            => '',
      'reference'                 => '',
      'user_id'                   => '',
      'location_id'               => '',
      'source_identifier'         => '',
      'source_url'                => '',
      'processed_at'              => date_i18n( 'Y-m-d H:i:s' ),
      'device_id'                 => '',
      'phone'                     => '',
      'customer_locale'           => '',
      'app_id'                    => 0,
      'browser_ip'                => '',
      'landing_site_ref'          => '',
      'order_number'              => 0,
      'discount_codes'            => '',
      'note_attributes'           => '',
      'payment_gateway_names'     => '',
      'processing_method'         => '',
      'checkout_id'               => 0,
      'source_name'               => '',
      'fulfillment_status'        => '',
      'tax_lines'                 => '',
      'tags'                      => '',
      'contact_email'             => '',
      'order_status_url'          => '',
      'line_items'                => '',
      'shipping_lines'            => '',
      'billing_address'           => '',
      'shipping_address'          => '',
      'fulfillments'              => '',
      'client_details'            => '',
      'refunds'                   => '',
      'customer'                  => ''
    );

  }


  /*

  Get Single Order

  */
  public function get_order($orderID = null) {

    global $wpdb;

    if ($orderID === null) {
      $orderID = get_the_ID();
    }

    if (get_transient('wps_order_single_' . $orderID)) {
      $results = get_transient('wps_order_single_' . $orderID);

    } else {

      $query = "SELECT orders.* FROM $this->table_name as orders WHERE orders.post_id = %d";
      $results = $wpdb->get_row( $wpdb->prepare($query, $orderID) );

      set_transient('wps_order_single_' . $orderID, $results);

    }

    return $results;

  }


  /*

  Get Orders

  */
  public function get_orders() {
    return $this->get_all_rows();
  }


  /*

  Insert orders

  */
  public function insert_orders($orders) {

    $DB_Settings_Connection = new Settings_Connection();
    $results = array();
    $progress = new Progress_Bar(new Config());
    $index = 1;

    foreach ($orders as $key => $order) {

      if (!Utils::isStillSyncing()) {
        wp_die();
      }

      // If product is visible on the Online Stores channel
      if (property_exists($order, 'created_at') && $order->created_at !== null) {

        // Converting to a fully qualified associative array
        $order = json_decode(json_encode($order), true);
        $results[] = $this->insert($order, 'order');

      }

      $progress->increment_current_amount('orders');

      $index++;

    }

    return $results;

  }


  /*

  Fired when product is update at Shopify

  */
  public function update_order($product) {

    /*

    TODO: Shopify may implement better sales channel checking in a future API. We should
    then check for Buy Button visibility as-well.

    */
    if (property_exists($product, 'published_at') && $product->published_at !== null) {

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
      if (property_exists($product, 'image') && !empty($product->image)) {
        $product->image = $product->image->src;
      }

      $results['variants']    = $DB_Variants->update_variant($product);
      $results['options']     = $DB_Options->update_option($product);
      $results['product']     = $this->update($product->id, $product);
      $results['image']       = $DB_Images->update_image($product);
      $results['collects']    = $DB_Collects->update_collects($product);

      // This takes care of syncing the custom post type content
      $results['product_cpt'] = CPT::wps_insert_or_update_product($product);

      $results['tags']        = $DB_Tags->update_tags($product, $results['product_cpt']);


    } else {
      $results['deleted_product'] = $this->delete_product($product, $product->id);

    }

    Transients::delete_cached_prices();
    Transients::delete_cached_variants();
    Transients::delete_cached_product_single();
    Transients::delete_cached_product_queries();

    return $results;

  }


  /*

  Fired when order is deleted at Shopify

  */
  public function delete_order($order, $orderID = null) {

    $Backend = new Backend(new Config());

    if ($orderID === null) {
      $orderID = $order->id;
    }

    $orderData = $this->get($orderID);

    $results['orders'] = $this->delete($orderID);

    return $results;

  }


  /*

  Fired when order is created at Shopify

  */
  public function create_order($order) {

    $orderWrapped = array();
    $orderWrapped[] = $order;
    $results = array();

    $results['orders'] = $this->insert_orders($orderWrapped);

    return $results;

  }


  /*

  Update orders

  */
  public function update_orders($orders) {

    $result = array();

    if (is_array($orders) && isset($order['id'])) {

      foreach ($orders as $key => $order) {
        $result[] = $this->update($order['id'], $order);
      }

    } else if (is_object($orders)) {

      $orders = json_decode(json_encode($orders), true);
      $result[] = $this->update($orders['id'], $orders);

    }

    return $result;

  }


  /*

  Rename primary key

  */
  public function rename_primary_key($order) {

    $orderCopy = $order;
    $orderCopy->order_id = $orderCopy->id;
    unset($orderCopy->id);

    return $orderCopy;

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
      `customer_id` bigint(100) unsigned DEFAULT NULL,
      `email` varchar(255) DEFAULT NULL,
      `closed_at` datetime,
      `created_at` datetime,
      `updated_at` datetime,
      `number` bigint(100) unsigned DEFAULT NULL,
      `note` longtext DEFAULT NULL,
      `token` varchar(255) DEFAULT NULL,
      `gateway` varchar(255) DEFAULT NULL,
      `test` varchar(255) DEFAULT NULL,
      `total_price` varchar(100) DEFAULT NULL,
      `subtotal_price` varchar(100) DEFAULT NULL,
      `total_weight` bigint(100) unsigned DEFAULT NULL,
      `total_tax` varchar(100) DEFAULT NULL,
      `taxes_included` tinyint(1) DEFAULT 0,
      `currency` varchar(100) DEFAULT NULL,
      `financial_status` varchar(100) DEFAULT NULL,
      `confirmed` tinyint(1) DEFAULT 0,
      `total_discounts` varchar(100) DEFAULT NULL,
      `total_line_items_price` varchar(100) DEFAULT NULL,
      `cart_token` varchar(100) DEFAULT NULL,
      `buyer_accepts_marketing` tinyint(1) DEFAULT 0,
      `name` varchar(100) DEFAULT NULL,
      `referring_site` varchar(255) DEFAULT NULL,
      `landing_site` varchar(255) DEFAULT NULL,
      `cancelled_at` datetime,
      `cancel_reason` varchar(255) DEFAULT NULL,
      `total_price_usd` varchar(255) DEFAULT NULL,
      `checkout_token` varchar(255) DEFAULT NULL,
      `reference` varchar(255) DEFAULT NULL,
      `user_id` bigint(100) unsigned DEFAULT NULL,
      `location_id` bigint(100) unsigned DEFAULT NULL,
      `source_identifier` varchar(255) DEFAULT NULL,
      `source_url` varchar(255) DEFAULT NULL,
      `processed_at` datetime,
      `device_id` bigint(100) unsigned DEFAULT NULL,
      `phone` varchar(100) DEFAULT NULL,
      `customer_locale` varchar(100) DEFAULT NULL,
      `app_id` bigint(100) unsigned DEFAULT NULL,
      `browser_ip` varchar(100) DEFAULT NULL,
      `landing_site_ref` varchar(255) DEFAULT NULL,
      `order_number` bigint(100) unsigned DEFAULT NULL,
      `discount_codes` varchar(100) DEFAULT NULL,
      `note_attributes` longtext DEFAULT NULL,
      `payment_gateway_names` varchar(100) DEFAULT NULL,
      `processing_method` varchar(100) DEFAULT NULL,
      `checkout_id` bigint(100) unsigned DEFAULT NULL,
      `source_name` varchar(100) DEFAULT NULL,
      `fulfillment_status` varchar(100) DEFAULT NULL,
      `tax_lines` longtext DEFAULT NULL,
      `tags` longtext DEFAULT NULL,
      `contact_email` varchar(100) DEFAULT NULL,
      `order_status_url` longtext DEFAULT NULL,
      `line_items` longtext DEFAULT NULL,
      `shipping_lines` longtext DEFAULT NULL,
      `billing_address` longtext DEFAULT NULL,
      `shipping_address` longtext DEFAULT NULL,
      `fulfillments` longtext DEFAULT NULL,
      `client_details` longtext DEFAULT NULL,
      `refunds` longtext DEFAULT NULL,
      `customer` longtext DEFAULT NULL,
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
