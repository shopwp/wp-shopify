<?php

use WPS\Webhooks;
use WPS\WS;
use WPS\Config;

$jsonData = file_get_contents('php://input');

if (Webhooks::webhook_verified($jsonData, WS::get_header_hmac())) {

  $WS = new WS(new Config());
  $WS->wps_ws_set_syncing_indicator(false, 1);

  error_log('---- Webhook verified order-create -----');
  $order = json_decode($jsonData);

  $WS->wps_ws_set_syncing_indicator(false, 0);

} else {
  error_log('WP Shopify Error - Unable to verify webhook response from order-create.php');
}
