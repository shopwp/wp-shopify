<?php

use WPS\DB\Settings_Connection;

$app = json_decode( file_get_contents('php://input') );

$Connection = new Settings_Connection();
$Connection->turn_on_need_cache_flush();
