<?php

use WPS\DB\Collections_Custom;
use WPS\DB\Collections_Smart;
use WPS\Collections;
use WPS\Config;

$DB_Collections_Custom = new Collections_Custom();
$DB_Collections_Smart = new Collections_Smart();
$Collections = new Collections(new Config());

$collection = json_decode( file_get_contents('php://input') );

if ($Collections->wps_is_smart_collection($collection) ) {
  $DB_Collections_Smart->delete_smart_collection($collection);

} else {
  $DB_Collections_Custom->delete_custom_collection($collection);

}
