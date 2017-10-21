<?php

use WPS\DB\Settings_Connection;
// use WPS\DB\Shop as DB_Shop;
// use WPS\Config as Config;
//
// $DB_Shop = new DB_Shop();

$app = json_decode( file_get_contents('php://input') );

// $DB_Products->create_product($app);
$Connection = new Settings_Connection();
$Connection->turn_on_need_cache_flush();
