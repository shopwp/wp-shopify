<?php

use WPS\Factories\Money_Factory;


/*

Tests the webhooks for Variants

Mock data comes from the "mock-data" folder and is taken directly from
the example output Shopify uses within their documentation found here:

https://help.shopify.com/api/reference/webhook

*/
class Test_Money extends WP_UnitTestCase {

  protected static $Money;

  static function wpSetUpBeforeClass() {

    // Assemble
    self::$Money = Money_Factory::build();

  }


  /*

  Should rename payload key to lookup key

  */
  function test_it_should_get_avail_money_formats() {

    $avail_money_formats = self::$Money->get_avail_money_formats();

    $this->assertInternalType('array', $avail_money_formats);
    $this->assertCount(5, $avail_money_formats);

  }


  /*

  It should rename payload key to lookup key

  */
  function test_it_should_get_current_money_format() {

    $current_money_format = self::$Money->get_current_money_format();

    $this->assertInternalType('string', $current_money_format);
    $this->assertRegexp('/{{/', $current_money_format);
    $this->assertRegexp('/}}/', $current_money_format);

  }


  /*

  It should replace amount front delimiter

  */
  function test_it_should_replace_amount_front_delimiter() {

    $test_string = '${{amount_no_decimals}} USD';
    $result = self::$Money->replace_amount_front_delimiter($test_string);

    $this->assertNotRegExp('/{{/', $result);

  }


  /*

  It should replace amount back delimiter

  */
  function test_it_should_replace_amount_back_delimiter() {

    $test_string = '${{amount_no_decimals}} USD';
    $result = self::$Money->replace_amount_back_delimiter($test_string);

    $this->assertNotRegExp('/}}/', $result);

  }


  /*

  It should replace amount space delimiter

  */
  function test_it_should_replace_amount_space_delimiter() {

    $test_string = '$ amount no decimals USD';
    $result = self::$Money->replace_amount_space_delimiter($test_string);

    $this->assertNotRegExp('/ /', $result);

  }


  /*

  It should replace amount space delimiter

  */
  function test_it_should_find_amount_no_decimals_format_from_explosion() {

    $exploded = ['$', 'amount_no_decimals', ' ', 'USD'];

    $result = self::$Money->find_format_from_explosion($exploded);

    $this->assertEquals('amount_no_decimals', $result);

  }


  /*

  It should replace amount space delimiter

  */
  function test_it_should_find_amount_format_from_explosion() {

    $exploded = ['$', 'amount', ' ', 'USD', [], false, 1000];

    $result = self::$Money->find_format_from_explosion($exploded);

    $this->assertEquals('amount', $result);

  }


  /*

  It should replace amount space delimiter

  */
  function test_it_should_find_amount_with_comma_separator_format_from_explosion() {

    $exploded = ['$', 'amount_with_comma_separator', ' ', 'USD', [], false, 1000];

    $result = self::$Money->find_format_from_explosion($exploded);

    $this->assertEquals('amount_with_comma_separator', $result);

  }


  /*

  It should replace amount space delimiter

  */
  function test_it_should_find_amount_no_decimals_with_comma_separator_format_from_explosion() {

    $exploded = ['$', 'amount_no_decimals_with_comma_separator', ' ', 'USD', [], false, 1000];

    $result = self::$Money->find_format_from_explosion($exploded);

    $this->assertEquals('amount_no_decimals_with_comma_separator', $result);

  }


  /*

  It should replace amount space delimiter

  */
  function test_it_should_find_amount_with_apostrophe_separator_format_from_explosion() {

    $exploded = ['$', 'amount_with_apostrophe_separator', ' ', 'USD', [], false, 1000];

    $result = self::$Money->find_format_from_explosion($exploded);

    $this->assertEquals('amount_with_apostrophe_separator', $result);

  }


  /*

  It should extract format name

  */
  function test_it_should_extract_format_name_A() {

    $format_A = '${{amount}} USD';

    $found_format_A = self::$Money->extract_format_name($format_A);

    $this->assertEquals('amount', $found_format_A);

  }


  /*

  It should extract format name

  */
  function test_it_should_extract_format_name_B() {

    $format_B = '${{amount}}USD';

    $found_format_B = self::$Money->extract_format_name($format_B);

    $this->assertEquals('amount', $found_format_B);

  }


  /*

  It should extract format name

  */
  function test_it_should_extract_format_name_C() {

    $format_C = '${{amount}}______';

    $found_format_C = self::$Money->extract_format_name($format_C);

    $this->assertEquals('amount', $found_format_C);

  }


  /*

  It should extract format name

  */
  function test_it_should_extract_format_name_D() {

    $format_D = '{{{amount}}}';

    $found_format_D = self::$Money->extract_format_name($format_D);

    $this->assertEquals('amount', $found_format_D);

  }


  /*

  It should extract format name

  */
  function test_it_should_extract_format_name_E() {

    $format_E = 'amount';

    $found_format_E = self::$Money->extract_format_name($format_E);

    $this->assertEquals('amount', $found_format_E);

  }


  /*

  It should extract format name

  */
  function test_it_should_extract_format_name_F() {

    $format_F = '{amount}';

    $found_format_F = self::$Money->extract_format_name($format_F);

    $this->assertEquals('amount', $found_format_F);

  }


  /*

  It should extract format name

  */
  function test_it_should_extract_format_name_G() {

    $format_G = '{{amount }}!! ok this is really cool';

    $found_format_G = self::$Money->extract_format_name($format_G);

    $this->assertEquals('amount', $found_format_G);

  }


  /*

  It should extract format name

  */
  function test_it_should_find_format_a_from_avail_list() {

    $array_key_found = self::$Money->search_format_from_avail_list('amount', self::$Money->get_avail_money_formats());

    $this->assertNotFalse($array_key_found);

  }


  /*

  It should extract format name

  */
  function test_it_should_find_format_b_from_avail_list() {

    $array_key_found = self::$Money->search_format_from_avail_list('amount_no_decimals', self::$Money->get_avail_money_formats());

    $this->assertNotFalse($array_key_found);

  }


  /*

  It should extract format name

  */
  function test_it_should_find_format_c_from_avail_list() {

    $array_key_found = self::$Money->search_format_from_avail_list('amount_with_comma_separator', self::$Money->get_avail_money_formats());

    $this->assertNotFalse($array_key_found);

  }


  /*

  It should extract format name

  */
  function test_it_should_find_format_d_from_avail_list() {

    $array_key_found = self::$Money->search_format_from_avail_list('amount_no_decimals_with_comma_separator', self::$Money->get_avail_money_formats());

    $this->assertNotFalse($array_key_found);

  }


  /*

  It should extract format name

  */
  function test_it_should_find_format_e_from_avail_list() {

    $array_key_found = self::$Money->search_format_from_avail_list('amount_with_apostrophe_separator', self::$Money->get_avail_money_formats());

    $this->assertNotFalse($array_key_found);

  }


  /*

  It should return format from found key

  */
  function test_it_should_return_amount_format_from_avail_list() {

    $array_key_found = self::$Money->return_format_from_found_key(0, self::$Money->get_avail_money_formats());

    $this->assertEquals('amount', $array_key_found);

  }


  /*

  It should return format from found key

  */
  function test_it_should_return_amount_no_decimals_format_from_avail_list() {

    $array_key_found = self::$Money->return_format_from_found_key(1, self::$Money->get_avail_money_formats());

    $this->assertEquals('amount_no_decimals', $array_key_found);

  }


  /*

  It should return format from found key

  */
  function test_it_should_return_amount_with_comma_separator_format_from_avail_list() {

    $array_key_found = self::$Money->return_format_from_found_key(2, self::$Money->get_avail_money_formats());

    $this->assertEquals('amount_with_comma_separator', $array_key_found);

  }


  /*

  It should return format from found key

  */
  function test_it_should_return_amount_no_decimals_with_comma_separator_format_from_avail_list() {

    $array_key_found = self::$Money->return_format_from_found_key(3, self::$Money->get_avail_money_formats());

    $this->assertEquals('amount_no_decimals_with_comma_separator', $array_key_found);

  }


  /*

  It should return format from found key

  */
  function test_it_should_return_amount_with_apostrophe_separator_format_from_avail_list() {

    $array_key_found = self::$Money->return_format_from_found_key(4, self::$Money->get_avail_money_formats());

    $this->assertEquals('amount_with_apostrophe_separator', $array_key_found);

  }


  /*

  It should return format from found key

  */
  function test_it_should_get_currency_symbol() {

    $symbol_usd = self::$Money->get_currency_symbol('USD');
    $symbol_eur = self::$Money->get_currency_symbol('EUR');
    $symbol_gbp = self::$Money->get_currency_symbol('GBP');
    $symbol_jpy = self::$Money->get_currency_symbol('JPY');
    $symbol_cny = self::$Money->get_currency_symbol('CNY');
    $symbol_rub = self::$Money->get_currency_symbol('RUB');

    $this->assertEquals('$', $symbol_usd);
    $this->assertRegexp('/€/', $symbol_eur);
    $this->assertEquals('£', $symbol_gbp);
    $this->assertEquals('¥', $symbol_jpy);
    $this->assertRegexp('/CN¥/', $symbol_cny);
    $this->assertRegexp('/руб/', $symbol_rub);

  }


  /*

  It should have more than one price

  */
  function test_it_should_has_more_than_one_price() {

    $result = self::$Money->has_more_than_one_price(2);

    $this->assertTrue($result);

  }



}
