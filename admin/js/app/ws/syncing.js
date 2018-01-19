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
  sanitizeErrorResponse,
  returnCustomError
} from '../utils/utils-data';

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
      console.error('streamConnection', error);
      reject( sanitizeErrorResponse(error) );

    }

  });

}


/*

Sync Shop Data

*/
function syncShop() {

  return new Promise(async function syncShopHandler(resolve, reject) {

    if (syncIsCanceled()) {

      reject();
      return;
    }

    try {
      var shop = await streamShop(); // wps_insert_shop
      resolve(shop);

    } catch(error) {
      console.error('streamShop', error);
      reject( sanitizeErrorResponse(error) );

    }

  });

}


/*

Syncing Products

*/
function syncProducts() {

  return new Promise(async function syncProductsHandler(resolve, reject) {

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var products = await streamProducts();

      if (isWordPressError(products)) {
        console.error('streamProducts isWordPressError', products);
        throw products.data;

      } else {
        resolve(products);
      }

    } catch(error) {
      console.error('streamProducts', error);
      reject(error);

    }

  });

}


/*

Sync Collects

*/
function syncCollects() {

  return new Promise(async function syncCollectsHandler(resolve, reject) {

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {
      var collects = await streamCollects(); // wps_insert_collects
      resolve(collects);

    } catch(error) {
      console.error('streamCollects', error);
      reject( sanitizeErrorResponse(error) );

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
      console.error('streamSmartCollections', error);
      reject( sanitizeErrorResponse(error) );

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

      reject( sanitizeErrorResponse(error) );
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

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {
      var orders = await streamOrders(); // wps_insert_orders
      resolve(orders);

    } catch(error) {
      console.error('streamOrders', error);
      reject( sanitizeErrorResponse(error) );

    }

  });

}


/*

Syncing Customers
TODO: Can we combine all of these syncing functions into a more generalized function?

*/
function syncCustomers() {

  return new Promise(async function syncCustomersHandler(resolve, reject) {

    if (syncIsCanceled()) {
      reject();
      return;
    }

    try {

      var customers = await streamCustomers(); // wps_insert_customers
      resolve(customers);

    } catch(error) {
      console.error('streamCustomers', error);
      reject( sanitizeErrorResponse(error) );

    }

  });

}


/*

Sync Shop Data

*/
function syncImageAlt() {

  return new Promise(async function syncImageAltHandler(resolve, reject) {

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
        console.error('altText', altText);
        reject(altText.data);

      } else {
        altText = altText.data;
      }

      resolve(altText);

    } catch(error) {
      console.error('insertAltText', error);
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
        throw connectionData.data;

      } else if (isError(connectionData)) {
        throw connectionData;
      }

      resolve(connectionData);

    } catch (errors) {

      console.error("saveConnection", saveConnection);

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
        throw saveCountsResponse.data;

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

        throw removePluginDataResp.data;

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
        throw syncPluginDataResp.data;

      } else if (isError(syncPluginDataResp)) {
        throw syncPluginDataResp;

      }

      resolve(syncPluginDataResp);

    } catch (errors) {
      console.error("errors: ", errors);
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
