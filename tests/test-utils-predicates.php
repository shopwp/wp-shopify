<?php

use WPS\Utils\Predicates;


/*

Tests Utils functions

*/
class Test_Utils_Predicates extends WP_UnitTestCase {

	/*

  Responsible array should not be empty

  */
	function test_it_should_be_int() {

    $result_one     = Predicates::is_int(1);
    $result_two     = Predicates::is_int('1');
    $result_three   = Predicates::is_int(-1);
    $result_four    = Predicates::is_int(0);
    $result_five    = Predicates::is_int(false);
    $result_six     = Predicates::is_int([]);

    $this->assertInternalType('boolean', $result_one);
    $this->assertTrue($result_one);

    $this->assertInternalType('boolean', $result_two);
    $this->assertFalse($result_two);

    $this->assertInternalType('boolean', $result_three);
    $this->assertTrue($result_three);

    $this->assertInternalType('boolean', $result_four);
    $this->assertTrue($result_four);

    $this->assertInternalType('boolean', $result_five);
    $this->assertFalse($result_five);

    $this->assertInternalType('boolean', $result_six);
    $this->assertFalse($result_six);

	}


	/*

  Responsible it should have a prop

  */
	function test_it_should_be_unsigned() {

    // True
    $result_one     = Predicates::is_unsigned(1);

    // False
    $result_two     = Predicates::is_unsigned('1');
    $result_three   = Predicates::is_unsigned(-1);
    $result_four    = Predicates::is_unsigned(0);
    $result_five    = Predicates::is_unsigned(false);
    $result_six     = Predicates::is_unsigned([]);


    $this->assertInternalType('boolean', $result_one);
    $this->assertTrue($result_one);

    $this->assertInternalType('boolean', $result_two);
    $this->assertfalse($result_two);

    $this->assertInternalType('boolean', $result_three);
    $this->assertFalse($result_three);

    $this->assertInternalType('boolean', $result_four);
    $this->assertFalse($result_four);

    $this->assertInternalType('boolean', $result_five);
    $this->assertFalse($result_five);

    $this->assertInternalType('boolean', $result_six);
    $this->assertFalse($result_six);

	}



}
