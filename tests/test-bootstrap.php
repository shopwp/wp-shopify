<?php

use WPS\Bootstrap;

/*

Tests Utils functions

*/
class Test_Bootstrap extends WP_UnitTestCase {

	function test_it_should_build_plugin_dependencies() {

		$builds = Bootstrap::plugin_build();

		foreach ($builds as $build) {

			$this->assertInternalType('object', $build);
			$this->assertContains('WPS\\', get_class($build));

		}

		$this->assertCount(44, $builds);

  }

}
