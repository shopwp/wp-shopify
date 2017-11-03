<?php

use WPS\DB\Settings_Connection;

error_log('===== order cancelled =====');

$Connection = new Settings_Connection();

$order = json_decode( file_get_contents('php://input') );

$Connection->turn_on_need_cache_flush();

error_log('===== order cancelled =====');
error_log(print_r($order, true));
error_log('===== /order cancelled =====');
