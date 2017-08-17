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

      if (error.hasOwnProperty('data')) {
        reject(error.data + ' (syncShop)');

      } else {
        reject(error + ' (syncShop)');

      }

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

      console.log('errorerror: ', error);

      if (error.hasOwnProperty('data')) {
        reject(error.data + ' (syncProducts)');

      } else {
        reject(error + ' (syncProducts)');

      }

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

      if (error.hasOwnProperty('data')) {
        console.log("errorrrrr: ", error);
        reject(error.data + ' ((syncCollects)');

      } else {
        console.log("errorrzzzz: ", error);
        reject(error + ' (syncCollects)');

      }

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

      if (error.hasOwnProperty('data')) {
        reject(error.data + ' (syncSmartCollections)');

      } else {
        reject(error + ' (syncSmartCollections)');

      }


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

      console.log('OKOKOKOK: ', customCollections);

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
