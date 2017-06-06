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
  insertSmartCollections
} from '../ws/ws';

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
      console.log('connectionData: ', connectionData);

    } catch(error) {
      reject(error);

    }

    //
    // 2. Send to server
    //
    try {
      var connection = await insertConnectionData(connectionData);
      console.log('connection: ', connection);

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
      console.log('shopData: ', shopData);

    } catch(error) {
      reject(error);

    }

    //
    // 2. Send to server
    //
    try {
      var shop = await insertShopData(shopData);

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
      pages;

  return new Promise(async function streamProductsHandler(resolve, reject) {

    //
    // 1. Get products count
    //
    try {

      productCount = await getProductsCount();
      productCount = productCount.count;
      console.log('productCount: ', productCount);

    } catch(error) {
      console.log('HERE 1');
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

        // console.log("newProductssss: ", newProducts);

        if (Array.isArray(newProducts)) {
          products = R.concat(products, newProducts);
          currentPage += 1;

        } else {
          reject(newProducts);
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
      collectsCount = collectsCount.count;
      console.log('collectsCount: ', collectsCount);

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

        collects = R.concat(collects, await insertCollects(currentPage));
        currentPage += 1;
        console.log('collects inserted: ', collects);

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
      console.log("smartCollections inserted: ", smartCollections);
      resolve(smartCollections);

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
      console.log("customCollections inserted: ", customCollections);
      resolve(customCollections);

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
