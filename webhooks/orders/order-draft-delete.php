<?php

use WPS\DB\Settings_Connection;

error_log('===== order draft delete =====');

$Connection = new Settings_Connection();

$order = json_decode( file_get_contents('php://input') );

$Connection->turn_on_need_cache_flush();

error_log('===== order draft delete =====');
error_log(print_r($order, true));
error_log('===== /order draft delete =====');
