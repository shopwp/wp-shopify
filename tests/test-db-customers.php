<?php

use WPS\Factories\DB_Customers_Factory;

/*

Tests the webhooks for Customers

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Customers extends WP_UnitTestCase {

  protected static $DB_Customers;
  protected static $mock_data_customer;
  protected static $mock_data_customer_for_update;
  protected static $mock_existing_customer_id;
  protected static $lookup_key;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Customers                    = DB_Customers_Factory::build();
    self::$mock_data_customer              = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/customers/customer.json") );
    self::$mock_data_customer_for_update   = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/customers/customer-update.json") );
    self::$mock_existing_customer_id       = 698883932183;
    self::$lookup_key                      = self::$DB_Customers->lookup_key;

  }


  /*

  Mock: Product Create

  */
  function test_customer_create() {

    $result = self::$DB_Customers->insert(self::$mock_data_customer);

    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Update

  */
  function test_customer_update() {

    $results = self::$DB_Customers->update(self::$lookup_key, self::$mock_existing_customer_id, self::$mock_data_customer_for_update);
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_customer_delete() {

    $results = self::$DB_Customers->delete_rows(self::$lookup_key, self::$mock_existing_customer_id);
    $this->assertEquals(1, $results);

  }


  /*

  It should return the complete table name with suffix as string

  */
  function test_it_should_get_table_name() {

    $table_name = self::$DB_Customers->get_table_name();

    $this->assertInternalType('string', $table_name );
    $this->assertEquals('wptests_wps_customers', $table_name );

  }


  /*

  It should return only the table name suffix as string

  */
  function test_it_should_get_table_name_suffix() {

    $table_name_suffix = self::$DB_Customers->table_name_suffix;

    $this->assertInternalType('string', $table_name_suffix );
    $this->assertEquals('wps_customers', $table_name_suffix );

  }


}
