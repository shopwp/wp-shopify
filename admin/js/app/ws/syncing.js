import isError from 'lodash/isError';

import {
  streamConnection,
  streamShop,
  streamProducts,
  streamCollects,
  streamSmartCollections,
  streamCustomCollections,
  streamOrders,
  streamCustomers
} from './streaming';

import {
  isWordPressError
} from '../utils/utils';

import {
  registerWebhooks,
  insertAltText,
  setSyncingIndicator,
  saveCountsToSession,
  insertConnectionData,
  removePluginData
} from './ws';

import {
  setConnectionProgress,
  getCancelSync,
  syncIsCanceled
} from './localstorage';

import {
  syncPluginData
} from './middleware';

/*

Sync Webhooks

*/
function syncWebhooks(removalErrors) {

  return new Promise(async function syncWebhooksHandler(resolve, reject) {

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var webhooks = await registerWebhooks(removalErrors); // wps_ws_register_all_webhooks

      if (typeof webhooks === 'string' || webhooks === null) {

        resolve({
          success: false,
          data: false
        });

      }


      if (isWordPressError(webhooks)) {
        reject(webhooks);
      }


      if (syncIsCanceled()) {

        reject({
          success: false,
          data: false
        });
        return;
      }

      resolve(webhooks);


    } catch(error) {

      reject(error);

    }


  });

}


/*

Sync Connection

*/
function syncConnection() {

  return new Promise(async function syncConnectionHandler(resolve, reject) {

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var connection = await streamConnection(); // wps_insert_connection
      resolve(connection);

    } catch(error) {
      reject(error);

    }

  });

}


/*

Sync Shop Data

*/
function syncShop() {

  return new Promise(async function syncShopHandler(resolve, reject) {

    if (!WP_Shopify.selective_sync.all && !WP_Shopify.selective_sync.shop) {
      resolve();
      return;
    }

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {
      var shop = await streamShop(); // wps_insert_shop
      resolve(shop);

    } catch(error) {
      reject(error);

    }

  });

}


/*

Syncing Products

*/
function syncProducts() {

  return new Promise(async function syncProductsHandler(resolve, reject) {

    if (!WP_Shopify.selective_sync.all && !WP_Shopify.selective_sync.products) {
      resolve();
      return;
    }

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var products = await streamProducts();

      if (isWordPressError(products)) {
        throw products;

      } else {
        resolve(products);
      }

    } catch(error) {
      reject(error);

    }

  });

}


/*

Sync Collects

*/
function syncCollects() {

  return new Promise(async function syncCollectsHandler(resolve, reject) {

    if (!WP_Shopify.selective_sync.all && !WP_Shopify.selective_sync.products) {
      resolve();
      return;
    }

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var collects = await streamCollects(); // wps_insert_collects
      resolve(collects);

    } catch(error) {
      reject(error);

    }

  });

}


/*

Sync Smart Collections

*/
function syncSmartCollections() {

  return new Promise(async function syncSmartCollectionsHandler(resolve, reject) {

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var smartCollections = await streamSmartCollections(); // wps_insert_smart_collections_data
      resolve(smartCollections);

    } catch(error) {
      reject(error);

    }

  });

}


/*

Syncing Collections

*/
function syncCustomCollections() {

  return new Promise(async function syncCustomCollectionsHandler(resolve, reject) {

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var customCollections = await streamCustomCollections(); // wps_insert_custom_collections_data
      resolve(customCollections);
      return;

    } catch(error) {

      reject(error);
      return;

    }

  });

}


/*

Syncing Orders
TODO: Can we combine all of these syncing functions into a more generalized function?

*/
function syncOrders() {

  return new Promise(async function syncOrdersHandler(resolve, reject) {

    if (!WP_Shopify.selective_sync.all && !WP_Shopify.selective_sync.orders) {
      resolve();
      return;
    }

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var orders = await streamOrders(); // wps_insert_orders
      resolve(orders);

    } catch(error) {
      reject(error);

    }

  });

}


/*

Syncing Customers
TODO: Can we combine all of these syncing functions into a more generalized function?

*/
function syncCustomers() {

  return new Promise(async function syncCustomersHandler(resolve, reject) {

    if (!WP_Shopify.selective_sync.all && !WP_Shopify.selective_sync.customers) {
      resolve();
      return;
    }

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var customers = await streamCustomers(); // wps_insert_customers
      resolve(customers);

    } catch(error) {
      reject(error);

    }

  });

}


/*

Sync Shop Data

*/
function syncImageAlt() {

  return new Promise(async function syncImageAltHandler(resolve, reject) {

    if (!WP_Shopify.selective_sync.all && !WP_Shopify.selective_sync.products) {
      resolve();
      return;
    }

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var altText = await insertAltText();

      if (typeof altText === 'string' || altText === null) {
        resolve();
      }

      if (isWordPressError(altText)) {
        reject(altText);

      } else {
        altText = altText.data;
      }

      resolve(altText);

    } catch(error) {
      reject(error);

    }

  });

}


/*

Sync Webhooks

*/
function syncOn() {

  return new Promise(async function syncOnHandler(resolve, reject) {

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var updatingSyncingIndicator = await setSyncingIndicator(1); // wps_ws_set_syncing_indicator

      if (isWordPressError(updatingSyncingIndicator)) {
        throw updatingSyncingIndicator;

      } else if (isError(updatingSyncingIndicator)) {
        throw updatingSyncingIndicator;
      }

      resolve(updatingSyncingIndicator);

    } catch(errors) {

      reject(errors);
      return;

    }

  });

}


/*

Step 1. Insert Connection Data

*/
function saveConnection(formData) {

  return new Promise(async function saveConnectionHandler(resolve, reject) {

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var connectionData = await insertConnectionData(formData); // wps_insert_connection

      if (isWordPressError(connectionData)) {
        throw connectionData;

      } else if (isError(connectionData)) {
        throw connectionData;
      }

      resolve(connectionData);

    } catch (errors) {

      reject(errors);
      return;

    }

  });

}


/*

Save Counts

*/
function saveCounts(allCounts) {

  return new Promise(async function saveCountsHandler(resolve, reject) {

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var saveCountsResponse = await saveCountsToSession(allCounts);

      if (isWordPressError(saveCountsResponse)) {
        throw saveCountsResponse;

      } else if (isError(saveCountsResponse)) {
        throw saveCountsResponse;

      }

      resolve(saveCountsResponse);

    } catch(errors) {

      reject(errors);
      return;

    }

  });

}


/*

Remove Existing Plugin Data

*/
function removeExistingData() {

  return new Promise(async function removeExistingDataHandler(resolve, reject) {

    try {

      var removePluginDataResp = await removePluginData();

      if (isWordPressError(removePluginDataResp)) {

        throw removePluginDataResp;

      } else if (isError(removePluginDataResp)) {

        throw removePluginDataResp;

      }

      resolve(removePluginDataResp);

    } catch(errors) {

      reject(errors);
      return;

    }

  });

}


/*

Sync all data

*/
function syncData() {

  return new Promise(async function syncDataHandler(resolve, reject) {

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      setConnectionProgress("true");

      var syncPluginDataResp = await syncPluginData();

      if (isWordPressError(syncPluginDataResp)) {
        throw syncPluginDataResp;

      } else if (isError(syncPluginDataResp)) {
        throw syncPluginDataResp;

      }

      resolve(syncPluginDataResp);

    } catch (errors) {

      reject(errors);
      return;

    }

  });

}

export {
  syncConnection,
  syncShop,
  syncProducts,
  syncCollects,
  syncSmartCollections,
  syncCustomCollections,
  syncOrders,
  syncCustomers,
  syncWebhooks,
  syncImageAlt,
  syncOn,
  syncData,
  saveConnection,
  saveCounts,
  removeExistingData
}
