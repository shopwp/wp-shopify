<?php

namespace WPS;

/*

Class Cart

*/
class Cart {

  protected static $instantiated = null;

  /*

  Initialize the class and set its properties.

  */
  public function __construct() {

  }


  /*

  Creates a new class if one hasn't already been created.
  Ensures only one instance is used.

  */
  public static function instance() {

    if (is_null(self::$instantiated)) {
      self::$instantiated = new self();
    }

    return self::$instantiated;

  }


  public static function wps_get_cart_id_from_order($order) {

    $cartID = array_filter($order->note_attributes, function($attribute) {
      return $attribute->name === 'cartID';
    });

    if (is_array($cartID) && isset($cartID[0]->value)) {
      return $cartID[0]->value;

    } else {
      return false;

    }

  }


}
