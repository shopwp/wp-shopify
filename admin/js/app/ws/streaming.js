import {
  getConnectionData,
  getShopData,
  getProductsCount,
  getCollectsCount,
  insertConnectionData,
  insertShopData,
  insertProductsData,
  insertCollects,
  insertCustomCollections,
  insertSmartCollections,
  uninstallProductData
} from '../ws/ws';

import {
  connectionInProgress
} from '../ws/localstorage';

import {
  isError
} from '../utils/utils';

/*

Stream Connection
Returns: Connection

*/
async function streamConnection() {

  return new Promise(async function streamConnectionHandler(resolve, reject) {

    //
    // 1. Get Shop Data
    //
    try {
      console.log("getConnectionData()");
      var connectionData = await getConnectionData();

      if (isError(connectionData)) {
        reject(connectionData.data);
      }

      if (!connectionInProgress()) {
        console.log("stopped at getConnectionData: ", connectionInProgress());
        reject('Syncing stopped during streamConnection');
      }

    } catch(error) {
      reject(error);

    }


    //
    // 2. Send to server
    //
    try {
      console.log("insertConnectionData()");
      var connection = await insertConnectionData(connectionData);

      if (!connectionInProgress()) {
        console.log("stopped at streamConnection: ", connectionInProgress());
        reject('Syncing stopped during streamConnection');
      }

    } catch(error) {
      reject(error);

    }

    resolve(connection);

  });

}


/*

Stream Shop
Returns Shop

*/
async function streamShop() {

  return new Promise(async function streamShopHandler(resolve, reject) {

    //
    // 1. Get Shop Data from Shopify
    // TODO: It's hard to tell at first glance whether we're making a call
    // to Shopify or our internal DB. We should prefix the function to
    // ensure this is clear. Phase 2.
    //
    try {
      console.log("getShopData()");
      var shopData = await getShopData();

      if (typeof shopData === 'string') {
        reject(shopData);
      }

      if (isError(shopData)) {
        reject(shopData.data);

      } else {
        shopData = shopData.data;
      }

      if (!connectionInProgress()) {
        console.log("stopped at streamShop: ", connectionInProgress());
        reject('Syncing stopped during streamShop');
      }

    } catch(error) {
      reject(error);

    }

    //
    // 2. Send to server
    //
    try {
      console.log("insertShopData()");
      var shop = await insertShopData(shopData);

      if (isError(shop)) {
        reject(shop.data);
      }

      if (!connectionInProgress()) {
        console.log("stopped at streamShop: ", connectionInProgress());
        reject('Syncing stopped during streamShop');
      }

    } catch(error) {
      reject(error);

    }

    resolve(shop);

  });

}


/*

Stream Products
Returns products

*/
async function streamProducts() {

	var productCount,
      products = [],
      productsCPT,
      pageSize = 250,
      currentPage = 1,
      pages,
      productData;

  return new Promise(async function streamProductsHandler(resolve, reject) {

    //
    // 0. Clean out data before syncing
    //
    try {
      console.log("uninstallProductData()");
      productData = await uninstallProductData();

      if (isError(productData)) {
        reject(productData.data);
      }

      if (!connectionInProgress()) {
        console.log("stopped at streamProducts: ", connectionInProgress());
        reject('Syncing stopped during streamProducts');
      }

    } catch(error) {

      reject(error);

    }


    //
    // 1. Get products count
    //
    try {
      console.log("getProductsCount()");
      productCount = await getProductsCount();

      if (isError(productCount)) {
        reject(productCount.data);

      } else {
        productCount = productCount.data.count;
      }

      if (!connectionInProgress()) {
        console.log("stopped at streamProducts: ", connectionInProgress());
        reject('Syncing stopped during streamProducts');
      }

    } catch(error) {
      reject(error);

    }

    //
    // 2. Get all products
    // TODO: Abstract out?
    //
    pages = Math.ceil(productCount / pageSize);


    // Run for each page of products
    while(currentPage <= pages) {

      try {
        console.log("insertProductsData()");
        var newProducts = await insertProductsData(currentPage);

        if (!connectionInProgress()) {
          console.log("stopped at streamProducts: ", connectionInProgress());
          reject('Syncing stopped during streamProducts');
        }

        if (isError(newProducts)) {
          console.log("stoppeddfsdfsdf: ", newProducts);
          reject(newProducts.data);

        } else {

          if (Array.isArray(newProducts.data.products)) {
            products = R.concat(products, newProducts.data.products);
            currentPage += 1;

          } else {
            reject(newProducts.data.products);

          }

        }

      } catch(error) {
        console.log("ouch: ", error);

        currentPage = pages+1;
        return reject(error);

      }

    }

    resolve(products);

  });

}


/*

Stream Collects
Returns Collects

*/
async function streamCollects() {

	var collectsCount;

  return new Promise(async function streamCollectsHandler(resolve, reject) {

    //
    // 1. Get collects count
    //
    try {

      console.log("getCollectsCount()");
      collectsCount = await getCollectsCount();

      if (isError(collectsCount)) {
        reject(collectsCount.data);

      } else {
        collectsCount = collectsCount.data.count;
      }

      if (!connectionInProgress()) {
        console.log("stopped at streamCollects: ", connectionInProgress());
        reject('Syncing stopped during streamCollects');
      }

    } catch(error) {

      reject(error);

    }

    //
    // 2. Get all collects
    //
    try {

      var pageSize = 250,
          currentPage = 1,
          pages = Math.ceil(collectsCount / pageSize),
          collects = [];

      // Runs for each page of collects until all done
      while(currentPage <= pages) {

        try {

          console.log("insertCollects()");
          var collectsNew = await insertCollects(currentPage);

          if (!connectionInProgress()) {
            console.log("stopped at insertCollects: ", connectionInProgress());
            reject('Syncing stopped during streamCollects');
          }

          if (isError(collectsNew)) {
            reject(collectsNew);
          }

        } catch(errorCollects) {
          reject(errorCollects);
        }

        collects = R.concat(collects, collectsNew.data);
        currentPage += 1;

      }

      resolve(collects);

    } catch(error) {

      reject(error);

    }

  });

}


/*

Stream Smart Collections
Returns Smart Collections

*/
async function streamSmartCollections() {

  return new Promise(async function streamSmartCollectionsHandler(resolve, reject) {

    try {

      console.log("insertSmartCollections()");
      var smartCollections = await insertSmartCollections();

      if (isError(smartCollections)) {
        reject(smartCollections.data);

      } else {
        resolve(smartCollections);
      }

      if (!connectionInProgress()) {
        console.log("stopped at streamSmartCollections: ", connectionInProgress());
        reject('Syncing stopped during streamSmartCollections');
      }

    } catch(error) {
      reject(error);

    }

  });

}


/*

Stream Custom Collections
Returns Smart Collections

*/
async function streamCustomCollections() {

  return new Promise(async function streamCustomCollectionsHandler(resolve, reject) {

    try {

      console.log("insertCustomCollections()");
      var customCollections = await insertCustomCollections();

      if (isError(customCollections)) {
        reject(customCollections.data);

      } else {
        resolve(customCollections);
      }

      if (!connectionInProgress()) {
        console.log("stopped at streamCustomCollections: ", connectionInProgress());
        reject('Syncing stopped during streamCustomCollections');
      }

    } catch(error) {

      reject(error);

    }

  });

}


export {
  streamConnection,
  streamShop,
  streamProducts,
  streamCollects,
  streamSmartCollections,
  streamCustomCollections
}
