<?php

use function WPS\Lib\remove_trailing_dash;
use function WPS\Lib\get_last_index;
use function WPS\Lib\get_last_value;
use function WPS\Lib\is_large_chunk;
use function WPS\Lib\split_namespace_into_chunks;
use function WPS\Lib\replace_underscores_with_dashes;
use function WPS\Lib\get_plugin_classes_path;
use function WPS\Lib\add_folder_name;
use function WPS\Lib\remove_packagename_from_chunks;
use function WPS\Lib\remove_filename_from_chunks;
use function WPS\Lib\build_folder_structure;
use function WPS\Lib\build_filename;
use function WPS\Lib\find_file_to_autoload;

use WPS\Utils;


/*

Tests Autoloader

*/
class Test_Autoloader extends WP_UnitTestCase {


  function test_it_should_remove_trailing_dash() {

    $results_one = remove_trailing_dash('-TEST-');
    $results_two = remove_trailing_dash('-TEST--');

    $this->assertEquals('-TEST', $results_one);
    $this->assertEquals('-TEST', $results_two);

  }


  function test_it_should_get_last_index() {

    $results_one    = get_last_index( ['1','2','3'] );
    $results_two    = get_last_index( ['1','2','3','4','5','6','7','8','9','10'] );
    $results_three  = get_last_index( [] );

    $this->assertEquals('2', $results_one);
    $this->assertEquals('9', $results_two);
    $this->assertEquals('0', $results_three);

  }


  function test_it_should_get_last_value() {

    $results_one    = get_last_value( ['1','2','3'] );
    $results_two    = get_last_value( ['1','2','3','4','5','6','7','8','9','10'] );
    $results_three  = get_last_value( [] );
    $results_four   = get_last_value( [1] );
    $results_five   = get_last_value( [false] );

    $this->assertInternalType('string', $results_one);
    $this->assertInternalType('string', $results_two);
    $this->assertInternalType('string', $results_three);
    $this->assertInternalType('string', $results_four);
    $this->assertInternalType('string', $results_five);

    $this->assertEquals('3', $results_one);
    $this->assertEquals('10', $results_two);
    $this->assertEquals('', $results_three);
    $this->assertEquals('1', $results_four);
    $this->assertEquals('', $results_five);

  }


  function test_is_large_chunk() {

    $results_one = is_large_chunk( ['1','2','3'] );
    $results_two = is_large_chunk( ['1'] );

    $this->assertInternalType('boolean', $results_one);
    $this->assertInternalType('boolean', $results_two);

    $this->assertTrue($results_one);
    $this->assertFalse($results_two);

  }


  function test_it_should_split_namespace_into_chunks() {

    $results_one = split_namespace_into_chunks('WPS\API');
    $results_two = split_namespace_into_chunks('\WPS\API\Settings');
    $results_three = split_namespace_into_chunks('WPS');
    $results_four = split_namespace_into_chunks('');

    $this->assertInternalType('array', $results_one);
    $this->assertCount(2, $results_one);

    $this->assertInternalType('array', $results_two);
    $this->assertCount(3, $results_two);

    $this->assertInternalType('array', $results_three);
    $this->assertCount(1, $results_three);

    $this->assertInternalType('array', $results_four);
    $this->assertCount(0, $results_four);

  }


  function test_it_should_replace_underscores_with_dashes() {

    $results_one = replace_underscores_with_dashes('WPS_API');
    $results_two = replace_underscores_with_dashes('_WPS_API_Settings');
    $results_three = replace_underscores_with_dashes('WPS_');
    $results_four = replace_underscores_with_dashes('');

    $this->assertInternalType('string', $results_one);
    $this->assertEquals('WPS-API', $results_one);

    $this->assertInternalType('string', $results_two);
    $this->assertEquals('-WPS-API-Settings', $results_two);

    $this->assertInternalType('string', $results_three);
    $this->assertEquals('WPS-', $results_three);

    $this->assertInternalType('string', $results_four);
    $this->assertEquals('', $results_four);

  }


  function test_it_should_get_plugin_classes_path() {

    $results_one = get_plugin_classes_path();

    $this->assertStringEndsWith('/wp-shopify-pro/classes/', $results_one);

  }


  function test_it_should_add_folder_name() {

    $results_one = add_folder_name('DB');
    $results_two = add_folder_name('DB_API');
    $results_three = add_folder_name('Utils one more');

    $this->assertInternalType('string', $results_one);
    $this->assertEquals('db/', $results_one);

    $this->assertInternalType('string', $results_two);
    $this->assertEquals('db_api/', $results_two);

    $this->assertInternalType('string', $results_three);
    $this->assertEquals('utils-one-more/', $results_three);

  }


  function test_it_should_remove_packagename_from_chunks() {

    $results_one    = remove_packagename_from_chunks(['WPS', 'DB', 'API'], 'WPS');
    $results_two    = remove_packagename_from_chunks(['WPS'], 'WPS');
    $results_three  = remove_packagename_from_chunks([], 'WPS');
    $results_four   = remove_packagename_from_chunks([false], 'WPS');

    $this->assertInternalType('array', $results_one);
    $this->assertEquals(['DB', 'API'], $results_one);

    $this->assertInternalType('array', $results_two);
    $this->assertEquals([], $results_two);

    $this->assertInternalType('array', $results_three);
    $this->assertEquals([], $results_three);

    $this->assertInternalType('array', $results_four);
    $this->assertEquals([], $results_four);

  }


  function test_it_should_remove_filename_from_chunks() {

    $results_one    = remove_filename_from_chunks(['DB', 'API']);
    $results_two    = remove_filename_from_chunks(['DB', 'Settings_General']);
    $results_three  = remove_filename_from_chunks([]);
    $results_four   = remove_filename_from_chunks([false]);

    $this->assertInternalType('array', $results_one);
    $this->assertEquals(['DB'], $results_one);

    $this->assertInternalType('array', $results_two);
    $this->assertEquals(['DB'], $results_two);

    $this->assertInternalType('array', $results_three);
    $this->assertEquals([], $results_three);

    $this->assertInternalType('array', $results_four);
    $this->assertEquals([], $results_four);

  }


  function test_it_should_build_folder_structure() {

    $results_one    = build_folder_structure(['DB', 'API']);
    $results_two    = build_folder_structure(['Utils', 'Common', 'ONE more']);
    $results_three  = build_folder_structure([]);
    $results_four   = build_folder_structure(false);

    $this->assertInternalType('string', $results_one);
    $this->assertEquals('db/api/', $results_one);

    $this->assertInternalType('string', $results_two);
    $this->assertEquals('utils/common/one-more/', $results_two);

    $this->assertInternalType('string', $results_three);
    $this->assertEquals('', $results_three);

    $this->assertInternalType('string', $results_four);
    $this->assertEquals('', $results_four);

  }


  function test_it_should_build_filename() {

    $results_one    = build_filename('DB_API');
    $results_two    = build_filename('Utils');
    $results_three  = build_filename('sETTINGS');
    $results_four   = build_filename('Factories-Admin_Notices');
    $results_five   = build_filename(false);

    $this->assertInternalType('string', $results_one);
    $this->assertEquals('class-db-api.php', $results_one);

    $this->assertInternalType('string', $results_two);
    $this->assertEquals('class-utils.php', $results_two);

    $this->assertInternalType('string', $results_three);
    $this->assertEquals('class-settings.php', $results_three);

    $this->assertInternalType('string', $results_four);
    $this->assertEquals('class-factories-admin-notices.php', $results_four);

    $this->assertInternalType('string', $results_five);
    $this->assertEquals('', $results_five);

  }



  function test_it_should_find_file_to_autoload() {

    $plugin_path = get_plugin_classes_path();

    $file_path_one = find_file_to_autoload('WPS\DBB', $plugin_path);
    $file_path_two = find_file_to_autoload('WPS', $plugin_path);
    $file_path_three = find_file_to_autoload('WPS\API', $plugin_path);
    $file_path_four = find_file_to_autoload('WPS\DB\Variants', $plugin_path);
    $file_path_five = find_file_to_autoload('WPS\Factories\Async_Processing_Posts_Products_Relationships_Factory', $plugin_path);

    $this->assertInternalType('boolean', $file_path_one);
    $this->assertFalse($file_path_one);

    $this->assertInternalType('boolean', $file_path_two);
    $this->assertFalse($file_path_two);

    $this->assertNotFalse($file_path_three);
    $this->assertNotFalse($file_path_four);
    $this->assertNotFalse($file_path_five);

  }


}
