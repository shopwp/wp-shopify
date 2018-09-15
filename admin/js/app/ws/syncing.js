import to from 'await-to-js';
import isError from 'lodash/isError';

import {
  streamShop,
  streamProducts,
  streamCollects,
  streamSmartCollections,
  streamCustomCollections,
  streamOrders,
  streamCustomers
} from './streaming';

import {
  registerWebhooks,
  insertAltText,
  setSyncingIndicator,
  saveCountsToSession,
  saveConnectionData,
  deleteOnlySyncedData
} from './ws';

import {
  getCancelSync,
  syncIsCanceled
} from './localstorage';


/*

Sync Webhooks

*/
function syncWebhooks(removalErrors) {
  return registerWebhooks(removalErrors) // register_all_webhooks
}


/*

Sync Shop Data

*/
function syncShop() {
  return streamShop() // insert_shop
}


/*

Syncing Products
get_bulk_products

*/
function syncProducts() {
  return streamProducts();
}


/*

Sync Collects

*/
function syncCollects() {
  return streamCollects();
}


/*

Sync Smart Collections

*/
function syncSmartCollections() {
  return streamSmartCollections();
}


/*

Syncing Collections

*/
function syncCustomCollections() {
  return streamCustomCollections();
}


/*

Syncing Orders

*/
function syncOrders() {
  return streamOrders();
}


/*

Syncing Customers

*/
function syncCustomers() {
  return streamCustomers();
}


/*

Turn syncing flag on. Changes the "is_syncing" column inside the connection table

*/
function syncOn() {
  return setSyncingIndicator(1);
}


/*

Turn syncing flag off. Changes the "is_syncing" column inside the connection table

*/
function syncOff() {
  return setSyncingIndicator(0);
}


/*

Step 1. Insert Connection Data
save_connection

*/
function saveConnection(formData) {
  return saveConnectionData(formData);
}


/*

Save Counts
insert_syncing_totals

*/
function saveCounts(allCounts, exclusions = []) {
  return saveCountsToSession(allCounts, exclusions);
}


/*

Remove Existing Plugin Data
delete_only_synced_data

*/
function removeExistingData() {
  return deleteOnlySyncedData();
}


export {
  syncShop,
  syncProducts,
  syncCollects,
  syncSmartCollections,
  syncCustomCollections,
  syncOrders,
  syncCustomers,
  syncWebhooks,
  syncOn,
  syncOff,
  saveConnection,
  saveCounts,
  removeExistingData
}
