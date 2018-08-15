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
  protected static $mockDataCustomer;
  protected static $mockDataCustomerForUpdate;
  protected static $mockDataCustomerID;


  static function setUpBeforeClass() {

    // Assemble
    self::$DB_Customers                    = DB_Customers_Factory::build();
    self::$mockDataCustomer                = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/customer.json") );
    self::$mockDataCustomerForUpdate       = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/customer-update.json") );
    self::$mockDataCustomerID              = self::$mockDataCustomer->id;

  }


  /*

  Mock: Product Create

  */
  function test_customer_create() {

    $result = self::$DB_Customers->insert(self::$mockDataCustomer, 'customer');
    $this->assertEquals(1, $result);

  }


  /*

  Mock: Product Update

  */
  function test_customer_update() {

    $results = self::$DB_Customers->update( self::$mockDataCustomerID, self::$mockDataCustomerForUpdate );
    $this->assertEquals(1, $results);

  }


  /*

  Mock: Product Delete

  */
  function test_customer_delete() {

    $results = self::$DB_Customers->delete( self::$mockDataCustomerID );
    $this->assertEquals(1, $results);

  }


}
