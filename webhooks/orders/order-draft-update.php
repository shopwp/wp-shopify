<?php

use WPS\DB\Settings_Connection;

$Connection = new Settings_Connection();

$order = json_decode( file_get_contents('php://input') );

$Connection->turn_on_need_cache_flush();

error_log('===== order draft update =====');
error_log(print_r($order, true));
error_log('===== /order draft update =====');
