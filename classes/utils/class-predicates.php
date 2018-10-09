<?php

namespace WPS\Utils;


if (!defined('ABSPATH')) {
	exit;
}


class Predicates {


  /*

  Is a valid type integer

  Tested: Yes

  */
  public static function is_int($maybe_int) {
    return is_int($maybe_int);
  }


  /*

  Is an unsigned integer

  Tested: Yes

  */
  public static function is_unsigned($maybe_unsigned) {

    if ( !self::is_int($maybe_unsigned) ) {
      return false;
    }

    return ( $maybe_unsigned > 0 ) ? true : ( ( $maybe_unsigned < 0 ) ? false : false );

  }
  

}
