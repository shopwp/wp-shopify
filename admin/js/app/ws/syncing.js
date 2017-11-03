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

/*

Sync Connection

*/
function syncConnection() {

  return new Promise(async function syncConnectionHandler(resolve, reject) {

    try {
      var connection = await streamConnection();
      resolve(connection);

    } catch(error) {

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
      var shop = await streamShop();
      resolve(shop);

    } catch(error) {

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
        throw products.data;

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

    try {
      var collects = await streamCollects();
      resolve(collects);

    } catch(error) {

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
      var smartCollections = await streamSmartCollections();
      resolve(smartCollections);

    } catch(error) {

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
      var customCollections = await streamCustomCollections();

      resolve(customCollections);

    } catch(error) {

      reject( sanitizeErrorResponse(error) );

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
      var orders = await streamOrders();

      resolve(orders);

    } catch(error) {

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
      var customers = await streamCustomers();

      resolve(customers);

    } catch(error) {

      reject( sanitizeErrorResponse(error) );

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
  syncCustomers
}
