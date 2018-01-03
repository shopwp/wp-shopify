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
  sanitizeErrorResponse
} from '../utils/utils-data';

import {
  isWordPressError
} from '../utils/utils';

import {
  registerWebhooks,
  insertAltText,
  getWebhooksCount
} from './ws';


/*

Sync Webhooks

*/
function syncWebhooks() {

  return new Promise(async function syncWebhooksHandler(resolve, reject) {

    /*

    2. Register Webhooks

    */
    try {

      var webhooks = await registerWebhooks(); // wps_ws_register_all_webhooks

      if (typeof webhooks === 'string' || webhooks === null) {

        resolve({
          success: false,
          data: false
        });

      }

      if (isWordPressError(webhooks)) {
        reject(webhooks.data);
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

    try {
      var connection = await streamConnection(); // wps_insert_connection
      console.log("syncConnection: ", connection);
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
  syncImageAlt
}
