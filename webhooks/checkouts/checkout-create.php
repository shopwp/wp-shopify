<?php

use WPS\DB\Settings_Connection;

$Connection = new Settings_Connection();

$checkout = json_decode( file_get_contents('php://input') );

$Connection->turn_on_need_cache_flush();

error_log('===== checkout create =====');
error_log(print_r($checkout, true));
error_log('===== /checkout create =====');
