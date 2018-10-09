<?php

use WPS\Factories\DB_Settings_Syncing_Factory;


/*

Tests the webhooks for General

General key currently doesn't update -- only adds or deletes

*/
class Test_DB_Syncing extends WP_UnitTestCase {

  protected static $DB_Settings_Syncing;

  static function wpSetUpBeforeClass() {

    // Assemble
    self::$DB_Settings_Syncing       = DB_Settings_Syncing_Factory::build();

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_have_default_values() {

  	$this->assertObjectHasAttribute('default_id', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_is_syncing', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_totals_shop', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_totals_smart_collections', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_totals_custom_collections', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_totals_products', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_totals_collects', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_totals_orders', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_totals_customers', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_totals_webhooks', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_step_total', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_step_current', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_current_amounts_shop', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_current_amounts_smart_collections', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_current_amounts_custom_collections', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_current_amounts_products', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_current_amounts_collects', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_current_amounts_orders', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_current_amounts_customers', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_current_amounts_webhooks', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_start_time', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_end_time', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_errors', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_syncing_warnings', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_finished_webhooks_deletions', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_finished_product_posts_relationships', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_finished_collection_posts_relationships', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_finished_data_deletions', self::$DB_Settings_Syncing);
  	$this->assertObjectHasAttribute('default_published_product_ids', self::$DB_Settings_Syncing);

  }


  /*

  It should have table info props

  */
  function test_it_should_have_table_info_props() {

    $this->assertObjectHasAttribute('table_name_suffix', self::$DB_Settings_Syncing);
    $this->assertObjectHasAttribute('table_name', self::$DB_Settings_Syncing);
    $this->assertObjectHasAttribute('version', self::$DB_Settings_Syncing);
    $this->assertObjectHasAttribute('primary_key', self::$DB_Settings_Syncing);
    $this->assertObjectHasAttribute('lookup_key', self::$DB_Settings_Syncing);
    $this->assertObjectHasAttribute('cache_group', self::$DB_Settings_Syncing);
    $this->assertObjectHasAttribute('type', self::$DB_Settings_Syncing);

  }


  /*

  It should update the current add to cart color

  */
  function test_it_should_match_default_values_and_cols_amount() {

    $cols_count = count( self::$DB_Settings_Syncing->get_columns() );
    $default_cols_count = count( self::$DB_Settings_Syncing->get_column_defaults() );

    $this->assertEquals($cols_count, $default_cols_count);

  }

}
