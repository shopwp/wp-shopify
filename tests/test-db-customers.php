<?php

use WPS\DB\Customers;

/*

Tests the webhooks for Customers

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Sync_Customers extends WP_UnitTestCase {

  protected static $Customers;
  protected static $mockDataCustomer;
  protected static $mockDataCustomerForUpdate;
  protected static $mockDataCustomerID;


  static function setUpBeforeClass() {

    // Assemble
    self::$Customers                       = new Customers();
    self::$mockDataCustomer                = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/customer.json") );
    self::$mockDataCustomerForUpdate       = json_decode( file_get_contents( dirname(__FILE__) . "/mock-data/customer-update.json") );
    self::$mockDataCustomerID              = self::$mockDataCustomer->id;

  }


  /*

  Mock: Product Create

  */
  function test_customer_create() {

    $result = self::$Customers->insert(self::$mockDataCustomer, 'customer');

    $this->assertTrue($result);

  }


  /*

  Mock: Product Update

  */
  function test_customer_update() {

    $results = self::$Customers->update( self::$mockDataCustomerID, self::$mockDataCustomerForUpdate );

    $this->assertTrue($results);

  }


  /*

  Mock: Product Delete

  */
  function test_customer_delete() {

    $results = self::$Customers->delete( self::$mockDataCustomerID );

    $this->assertTrue($results);

  }


}
