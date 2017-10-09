import {
  streamConnection,
  streamShop,
  streamProducts,
  streamCollects,
  streamSmartCollections,
  streamCustomCollections
} from './streaming';

import {
  sanitizeErrorResponse
} from '../utils/utils-data';

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
      resolve(products);

    } catch(error) {

      reject( sanitizeErrorResponse(error) );

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

export {
  syncConnection,
  syncShop,
  syncProducts,
  syncCollects,
  syncSmartCollections,
  syncCustomCollections
}
