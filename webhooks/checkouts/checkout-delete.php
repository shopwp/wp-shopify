<?php

use WPS\DB\Settings_Connection;

$Connection = new Settings_Connection();

$checkout = json_decode( file_get_contents('php://input') );

$Connection->turn_on_need_cache_flush();
