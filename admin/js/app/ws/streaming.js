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
      var connectionData = await getConnectionData();

      if (isError(connectionData)) {
        reject(connectionData.data);
      }

    } catch(error) {
      reject(error);

    }

    //
    // 2. Send to server
    //
    try {
      var connection = await insertConnectionData(connectionData);

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

      var shopData = await getShopData();

      if (typeof shopData === 'string') {
        reject(shopData);
      }

      if (isError(shopData)) {
        reject(shopData.data);

      } else {
        shopData = shopData.data;
      }


    } catch(error) {
      reject(error);

    }

    //
    // 2. Send to server
    //
    try {
      var shop = await insertShopData(shopData);

      if (isError(shop)) {
        reject(shop.data);
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

      productData = await uninstallProductData();

      if (isError(productData)) {
        reject(productData.data);

      }

    } catch(error) {

      console.log("===== productData error: ", error);
      reject(error);

    }


    //
    // 1. Get products count
    //
    try {

      productCount = await getProductsCount();

      if (isError(productCount)) {
        reject(productCount.data);

      } else {
        productCount = productCount.data.count;
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

        var newProducts = await insertProductsData(currentPage);

        if (isError(newProducts)) {
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

      collectsCount = await getCollectsCount();

      if (isError(collectsCount)) {
        reject(collectsCount.data);

      } else {
        collectsCount = collectsCount.data.count;
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
          var collectsNew = await insertCollects(currentPage);

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
      var smartCollections = await insertSmartCollections();

      if (isError(smartCollections)) {
        reject(smartCollections.data);

      } else {
        resolve(smartCollections);
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
      var customCollections = await insertCustomCollections();

      if (isError(customCollections)) {
        reject(customCollections.data);

      } else {
        resolve(customCollections);
      }

    } catch(error) {
;
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
