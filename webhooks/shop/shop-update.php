<?php

use WPS\DB\Shop;
use WPS\DB\Settings_Connection;

$Connection = new Settings_Connection();

$Shop = new Shop();
$shop = json_decode( file_get_contents('php://input') );

$Connection->turn_on_need_cache_flush();
$Shop->update_shop($shop);
