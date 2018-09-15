<?php

use WPS\Messages;

/*

Tests Messages functions

*/
class Test_Messages extends WP_UnitTestCase {

  /*

  Responsible for getting last index of an array

  */
  function test_it_should_get_saving_native_cpt_data() {

    $result = Messages::get('saving_native_cpt_data');

    $this->assertNotEquals(false, $result);
    $this->assertNotEquals(null, $result);
    $this->assertInternalType('string', $result);
    $this->assertEquals(Messages::$saving_native_cpt_data, $result);

  }


}
