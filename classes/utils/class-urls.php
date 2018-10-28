<?php

namespace WPS\Utils;


if (!defined('ABSPATH')) {
	exit;
}


class URLs {

  static public function get_extension($path) {

    $qpos = strpos($path, "?");

    if ( $qpos !== false) {
  		$path = substr($path, 0, $qpos);
  	}

    return pathinfo($path, PATHINFO_EXTENSION);

  }

}
