<?php


use WPS\Factories;


/*

Tests the webhooks for Customers

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_DB_Customers extends WP_UnitTestCase {

  protected static $DB_Customers;
  protected static $mock_data_customer;
  protected static $mock_data_customer_for_update;
  protected static $mock_existing_customer_id;
  protected static $lookup_key;


  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Customers                    = Factories\DB\Customers_Factory::build();
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


  /*

  It should update the current add to cart color

  */
  function test_it_should_have_default_values() {

    $this->assertObjectHasAttribute('default_customer_id', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_email', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_accepts_marketing', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_created_at', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_updated_at', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_first_name', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_last_name', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_orders_count', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_state', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_total_spent', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_last_order_id', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_note', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_verified_email', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_multipass_identifier', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_tax_exempt', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_phone', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_tags', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_last_order_name', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_default_address', self::$DB_Customers);
    $this->assertObjectHasAttribute('default_addresses', self::$DB_Customers);

  }


  /*

  It should have table info props

  */
  function test_it_should_have_table_info_props() {

    $this->assertObjectHasAttribute('table_name_suffix', self::$DB_Customers);
    $this->assertObjectHasAttribute('table_name', self::$DB_Customers);
    $this->assertObjectHasAttribute('version', self::$DB_Customers);
    $this->assertObjectHasAttribute('primary_key', self::$DB_Customers);
    $this->assertObjectHasAttribute('lookup_key', self::$DB_Customers);
    $this->assertObjectHasAttribute('cache_group', self::$DB_Customers);
    $this->assertObjectHasAttribute('type', self::$DB_Customers);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_match_default_values_and_cols_amount() {

    $cols = self::$DB_Customers->get_columns();
    $default_cols = self::$DB_Customers->get_column_defaults();

    $col_difference = array_diff_key($cols, $default_cols);

    $this->assertCount(1, $col_difference);
    $this->assertArrayHasKey('id', $col_difference);

  }


}
