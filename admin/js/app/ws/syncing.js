import {
  streamConnection,
  streamShop,
  streamProducts,
  streamCollects,
  streamSmartCollections,
  streamCustomCollections
} from './streaming';


/*

Sync Connection

*/
function syncConnection() {
  return new Promise(async function syncConnectionHandler(resolve, reject) {

    try {
      var connection = await streamConnection();
      resolve(connection);

    } catch(error) {
      console.log('You died, try again 1', error);
      reject(error);

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
      console.log('You died, try again 2', error);
      reject(error);

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

      console.log('You died, try again 3', error);
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

      console.log('You died, try again 4', error);
      reject(error);

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

      console.log('You died, try again 5', error);
      reject(error);

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

      console.log('You died, try again 6', error);
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
  syncCustomCollections
}
