<?php

use WPS\Factories\DB_Orders_Factory;

/*

Tests the webhooks for Orders

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_DB_Orders extends WP_UnitTestCase {

  protected static $DB_Orders;
  protected static $mock_order;
  protected static $mock_order_for_update;
  protected static $mock_existing_order_id;
  protected static $mock_order_update;
  protected static $mock_order_insert;
  protected static $mock_order_delete;
  protected static $lookup_key;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Orders                 = DB_Orders_Factory::build();
    self::$mock_order                = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/orders/order.json") );
    self::$mock_order_for_update     = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/orders/order-update.json") );
    self::$mock_order_update         = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/orders/orders-update.json") );
    self::$mock_order_insert         = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/orders/orders-insert.json") );
    self::$mock_order_delete         = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/orders/orders-delete.json") );
    self::$mock_existing_order_id    = self::$mock_order_for_update->id;
    self::$lookup_key                = self::$DB_Orders->lookup_key;

  }


  /*

  Mock: Order Create

  */
  function test_order_create() {

    $result = self::$DB_Orders->insert(self::$mock_order);

    $this->assertEquals(1, $result);

  }


  /*

  Mock: Order Update

  */
  function test_order_update() {

    $results = self::$DB_Orders->update(self::$lookup_key, self::$mock_existing_order_id, self::$mock_order_for_update);

    $this->assertEquals(1, $results);

  }


  /*

  Mock: Order Delete

  */
  function test_order_delete() {

    $results = self::$DB_Orders->delete_rows(self::$lookup_key, self::$mock_existing_order_id);

    $this->assertEquals(1, $results);

  }


  /*

  Should update order

  */
  function test_it_should_update_order() {

    $update_item_result = self::$DB_Orders->update_items_of_type(self::$mock_order_update);

    $this->assertEquals(1, $update_item_result);

  }


  /*

  Should update order

  */
  function test_it_should_insert_order() {

    $insert_item_result = self::$DB_Orders->insert_items_of_type(self::$mock_order_insert);

    $this->assertEquals(1, $insert_item_result);

  }


  /*

  Should update order

  */
  function test_it_should_delete_order() {

    $delete_item_result = self::$DB_Orders->delete_items_of_type(self::$mock_order_delete);

    $this->assertEquals(1, $delete_item_result);

  }


  /*

  Should rename payload key to lookup key

  */
  function test_it_should_maybe_rename_to_lookup_key() {

    $rename_result = self::$DB_Orders->maybe_rename_to_lookup_key(self::$mock_order_insert);

    $this->assertObjectHasAttribute(self::$DB_Orders->lookup_key, $rename_result);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Orders->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_orders', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Orders->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_orders', $table_name_suffix );

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_have_default_values() {

    $this->assertObjectHasAttribute('default_id', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_order_id', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_customer_id', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_email', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_closed_at', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_created_at', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_updated_at', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_number', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_note', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_token', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_gateway', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_total_price', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_subtotal_price', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_total_weight', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_total_tax', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_taxes_included', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_currency', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_financial_status', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_confirmed', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_total_discounts', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_total_line_items_price', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_cart_token', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_buyer_accepts_marketing', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_name', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_referring_site', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_landing_site', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_cancelled_at', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_cancel_reason', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_total_price_usd', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_checkout_token', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_reference', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_user_id', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_location_id', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_source_identifier', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_source_url', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_processed_at', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_device_id', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_phone', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_customer_locale', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_app_id', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_browser_ip', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_landing_site_ref', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_order_number', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_discount_codes', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_note_attributes', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_payment_gateway_names', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_processing_method', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_checkout_id', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_source_name', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_fulfillment_status', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_tax_lines', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_tags', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_contact_email', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_order_status_url', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_line_items', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_shipping_lines', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_billing_address', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_shipping_address', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_fulfillments', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_client_details', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_refunds', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_customer', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_test', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_discount_applications', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_admin_graphql_api_id', self::$DB_Orders);
    $this->assertObjectHasAttribute('default_payment_details', self::$DB_Orders);

  }


  /*

  It should have table info props

  */
  function test_it_should_have_table_info_props() {

    $this->assertObjectHasAttribute('table_name_suffix', self::$DB_Orders);
    $this->assertObjectHasAttribute('table_name', self::$DB_Orders);
    $this->assertObjectHasAttribute('version', self::$DB_Orders);
    $this->assertObjectHasAttribute('primary_key', self::$DB_Orders);
    $this->assertObjectHasAttribute('lookup_key', self::$DB_Orders);
    $this->assertObjectHasAttribute('cache_group', self::$DB_Orders);
    $this->assertObjectHasAttribute('type', self::$DB_Orders);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_match_default_values_and_cols_amount() {

    $cols_count = count( self::$DB_Orders->get_columns() );
    $default_cols_count = count( self::$DB_Orders->get_column_defaults() );

    $this->assertEquals($cols_count, $default_cols_count);

  }


}
