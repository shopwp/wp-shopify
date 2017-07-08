<?php

namespace WPS;

/*

Class Transients

*/
class Transients {

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


  /*

  check_rewrite_rules

  */
  public static function check_rewrite_rules() {

    if (get_transient('wps_settings_updated') !== false) {

      flush_rewrite_rules();
      delete_transient('wps_settings_updated');

    } else {

    }

  }


  /*

  Check Money Format

  */
  public static function check_money_format() {

    if (get_transient('wps_money_format_updated') !== false) {
      delete_transient('wps_money_format_updated');
    }

  }


  /*

  Check Money Format

  */
  public static function check_money_with_currency_format() {

    if (get_transient('wps_money_with_currency_format_updated') !== false) {
      delete_transient('wps_money_with_currency_format_updated');
    }

  }


  /*

  Delete price cache

  */
  public static function delete_cached_prices() {

    global $wpdb;

    $results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wps\_product\_price\_id\_%'");

    return $results;

  }


  /*

  Delete all transients

  */
  public static function delete_all_cache() {

    global $wpdb;

    $results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wps\_product\_%'");

    return $results;

  }


  /*

  Delete all transients

  */
  public static function delete_cached_variants() {

    global $wpdb;

    $results = $wpdb->query("DELETE FROM $wpdb->options WHERE `option_name` LIKE '%\_transient\_wps\_product\_with\_variants\_%'");

    return $results;

  }


}
