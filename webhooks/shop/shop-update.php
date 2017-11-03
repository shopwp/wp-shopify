<?php

use WPS\DB\Shop;
use WPS\DB\Settings_Connection;

$Connection = new Settings_Connection();

$DB_Shop = new Shop();
$shop = json_decode( file_get_contents('php://input') );

$Connection->turn_on_need_cache_flush();
$DB_Shop->update_shop($shop);

error_log('===== $shop =====');
error_log(print_r($shop, true));
error_log('===== /$shop =====');
