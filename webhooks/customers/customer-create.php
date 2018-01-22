<?php

use WPS\Webhooks;
use WPS\WS;
use WPS\Config;
use WPS\DB\Customers;

$jsonData = file_get_contents('php://input');

if (Webhooks::webhook_verified($jsonData, WS::get_header_hmac())) {

  // TODO: Kinda hacky, we should rethink how we set the syncing indicator
  $WS = new WS(new Config());
  $WS->wps_ws_set_syncing_indicator(false, 1);

  $Customers = new Customers();

  $customer = json_decode($jsonData);
  $Customers->insert_customers($customer);

  $WS->wps_ws_set_syncing_indicator(false, 0);

} else {
  error_log('WP Shopify Error - Unable to verify webhook response from customer-create.php');
}
