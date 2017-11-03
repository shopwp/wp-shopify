<?php

use WPS\DB\Settings_Connection;

$Connection = new Settings_Connection();

$customer = json_decode( file_get_contents('php://input') );

$Connection->turn_on_need_cache_flush();

error_log('===== customer enable =====');
error_log(print_r($customer, true));
error_log('===== /customer enable =====');
