import {
  getConnectionData,
  getShopData,
  getTotalCountsFromSession,
  insertConnectionData,
  insertShopData,
  insertProductsData,
  insertCollects,
  insertCustomCollections,
  insertSmartCollections,
  insertOrders,
  insertCustomers
} from '../ws/ws';

import {
  connectionInProgress
} from '../ws/localstorage';

import {
  isWordPressError
} from '../utils/utils';


/*

Construct Streaming Options

*/
function constructStreamingOptions(itemCount) {

  var pageSize = 250;

  return {
    currentPage: 1,
    pages: Math.ceil(itemCount / pageSize),
    items: []
  }

};


/*

Stream Connection
Returns: Connection

*/
async function streamConnection() {

  return new Promise(async function streamConnectionHandler(resolve, reject) {


    /*

    1. Get Shop Data

    */
    try {

      var connectionData = await getConnectionData();

      if (isWordPressError(connectionData)) {
        reject(connectionData.data);
        return;
      }

      if (!connectionInProgress()) {
        reject('Syncing stopped during streamConnection');
        return;
      }

      // If we already have an active connection ...
      if (connectionData.data.api_key) {
        resolve(connectionData);
      }

    } catch(error) {
      reject(error);
      return;
    }


    /*

    2. Send to server

    */
    try {

      var connection = await insertConnectionData(connectionData); // wps_insert_connection

      if (isWordPressError(connection)) {
        reject(connection.data);
        return;
      }

      if (!connectionInProgress()) {
        reject('Syncing stopped during streamConnection');
        return;
      }

    } catch(error) {
      reject(error);
      return;

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

    /*

    1. Get Shop Data from Shopify

    TODO: It's hard to tell at first glance whether we're making a call
    to Shopify or our internal DB. We should prefix the function to
    ensure this is clear. Phase 2.

    */
    try {

      var shopData = await getShopData();

      if (typeof shopData === 'string') {
        reject(shopData);
        return;
      }

      if (isWordPressError(shopData)) {
        reject(shopData);
        return;
      }

      if (!connectionInProgress()) {
        reject('Syncing stopped during streamShop');
      }

      shopData = shopData.data;

    } catch(error) {
      reject(error);
      return;
    }


    /*

    2. Send shop data to server

    */
    try {

      var shop = await insertShopData(shopData); // wps_insert_shop

      if (isWordPressError(shop)) {
        reject(shop);
        return;
      }

      if (!connectionInProgress()) {
        reject({ success: false, data: 'Syncing stopped during streamShop'});
        return;
      }

    } catch(error) {
      reject(error);
      return;
    }

    resolve(shop);

  });

}


/*

Stream Products
Returns products

*/
async function streamProducts() {

  return new Promise(async function streamProductsHandler(resolve, reject) {

    /*

    1. Get products count

    */
    try {

      var itemCount = await getTotalCountsFromSession(); // get_total_counts

      if (isWordPressError(itemCount)) {
        reject(itemCount.data);
        return;
      }

      if (!connectionInProgress()) {
        reject('Syncing stopped during streamProducts');
        return;
      }

      itemCount = itemCount.data.products;

    } catch(error) {
      reject(error);
      return;
    }


    /*

    2. Get all products

    */
    try {

      var { currentPage, pages, items } = constructStreamingOptions(itemCount);

      while(currentPage <= pages) {

        var itemsToAdd = await insertProductsData(currentPage); // wps_insert_products_data

        if (isWordPressError(itemsToAdd)) {
          reject(itemsToAdd);
          break;
        }

        if (!connectionInProgress()) {
          reject({ success: false, data: 'Syncing stopped during streamProducts'});
          break;
        }

        currentPage += 1;

      }

      resolve(itemsToAdd);
      return;

    } catch (error) {
      reject(error);
    }

  });

}


/*

Stream Collects
Returns Collects

*/
async function streamCollects() {

  return new Promise(async function streamCollectsHandler(resolve, reject) {

    /*

    1. Get collects count

    */
    try {

      var itemCount = await getTotalCountsFromSession(); // get_total_counts

      if (isWordPressError(itemCount)) {
        reject(itemCount.data);
        return;
      }

      if (!connectionInProgress()) {
        reject('Syncing stopped during streamCollects');
        return;
      }

      itemCount = itemCount.data.collects;

    } catch(error) {
      reject(error);
      return;
    }


    /*

    2. Insert all collects

    */
    try {

      var { currentPage, pages, items } = constructStreamingOptions(itemCount);

      // Runs for each page of collects until all done
      while(currentPage <= pages) {

        var itemsToAdd = await insertCollects(currentPage); // wps_insert_collects

        if (isWordPressError(itemsToAdd)) {
          reject(itemsToAdd);
          break;
        }

        if (!connectionInProgress()) {
          reject({ success: false, data: 'Syncing stopped during streamCollects'});
          break;
        }

        currentPage += 1;

      }

      resolve(itemsToAdd);
      return;

    } catch(error) {
      reject(error);
      return;

    }

  });

}


/*

Stream Smart Collections
Returns Smart Collections

*/
function streamSmartCollections() {

  return new Promise(async function streamSmartCollectionsHandler(resolve, reject) {

    /*

    1. Get Smart Collections Count

    */
    try {

      var itemCount = await getTotalCountsFromSession(); // get_total_counts

      if (isWordPressError(itemCount)) {
        reject(itemCount.data);
        return;
      }

      if (!connectionInProgress()) {
        reject('Syncing stopped during smart_collections getTotalCountsFromSession');
        return;
      }

      itemCount = itemCount.data.smart_collections;

    } catch(error) {

      reject(error);
      return;

    }


    /*

    2. Insert all Smart Collections

    */
    try {

      var { currentPage, pages, items } = constructStreamingOptions(itemCount);

      while(currentPage <= pages) {

        var itemsToAdd = await insertSmartCollections(); // wps_insert_smart_collections_data

        if (isWordPressError(itemsToAdd)) {
          reject(itemsToAdd.data);
          break;
        }

        if (!connectionInProgress()) {
          reject('Syncing stopped during streamSmartCollections');
          break;
        }

        currentPage += 1;

      }

      resolve(itemsToAdd);
      return;

    } catch(error) {
      reject(error);
      return;
    }

  });

}


/*

Stream Custom Collections
Returns Smart Collections

*/
async function streamCustomCollections() {

  return new Promise(async function streamCustomCollectionsHandler(resolve, reject) {

    /*

    1. Get Smart Collections Count

    */
    try {

      var itemCount = await getTotalCountsFromSession(); // get_total_counts

      if (isWordPressError(itemCount)) {
        reject(itemCount.data);
        return;
      }

      if (!connectionInProgress()) {
        reject('Syncing stopped during custom_collections getTotalCountsFromSession');
        return;
      }

      itemCount = itemCount.data.custom_collections;

    } catch(error) {
      reject(error);
      return;
    }


    /*

    2. Insert all Smart Collections

    */
    try {

      var { currentPage, pages, items } = constructStreamingOptions(itemCount);

      while(currentPage <= pages) {

        var itemsToAdd = await insertCustomCollections(); // wps_insert_custom_collections_data

        if (isWordPressError(itemsToAdd)) {
          reject(itemsToAdd.data);
          break;
        }

        if (!connectionInProgress()) {
          reject('Syncing stopped during insertCustomCollections');
          break;
        }

        currentPage += 1;

      }

      resolve(itemsToAdd);
      return;

    } catch(error) {
      reject(error);
      return;
    }

  });

}


/*

Stream Orders
Returns Orders

*/
async function streamOrders() {

  return new Promise(async function streamOrdersHandler(resolve, reject) {

    /*

    Step 1. Get Orders count

    */
    try {

      var itemCount = await getTotalCountsFromSession(); // get_total_counts

      if (isWordPressError(itemCount)) {
        reject(itemCount.data);
        return;
      }

      if (!connectionInProgress()) {
        reject('Syncing stopped during orders getTotalCountsFromSession');
        return;
      }

      itemCount = itemCount.data.orders;

    } catch(error) {
      reject(error);
      return;

    }


    /*

    Step 2. Insert Orders

    */
    try {

      var { currentPage, pages, items } = constructStreamingOptions(itemCount);

      while(currentPage <= pages) {

        var itemsToAdd = await insertOrders(); // wps_insert_orders

        if (isWordPressError(itemsToAdd)) {
          reject(itemsToAdd);
          break;
        }

        if (!connectionInProgress()) {
          reject({ success: false, data: 'Syncing stopped during streamOrders'});
          break;
        }

        currentPage += 1;

      }

      resolve(itemsToAdd);
      return;

    } catch(error) {
      reject(error);
      return;
    }

  });

}


/*

Stream Customers
Returns Customers

TODO: Combine with streamCustomers into a more generalized function

*/
async function streamCustomers() {

  return new Promise(async function streamCustomersHandler(resolve, reject) {

    /*

    Step 1. Get Customers count

    */
    try {

      var itemCount = await getTotalCountsFromSession(); // get_total_counts

      if (isWordPressError(itemCount)) {
        reject(itemCount.data);
        return;
      }

      if (!connectionInProgress()) {
        reject('Syncing stopped during customers streamCustomers');
        return;
      }

      itemCount = itemCount.data.customers;

    } catch(error) {
      reject(error);
      return;
    }


    /*

    Step 2. Insert Customers

    */
    try {

      var { currentPage, pages, items } = constructStreamingOptions(itemCount);

      while(currentPage <= pages) {

        var itemsToAdd = await insertCustomers(); // wps_insert_customers

        if (isWordPressError(itemsToAdd)) {
          reject(itemsToAdd);
          break;
        }

        if (!connectionInProgress()) {

          reject({
            success: false,
            data: 'Syncing stopped during streamCustomers'
          });

          break;

        }

        currentPage += 1;

      }

      resolve(itemsToAdd);
      return;

    } catch(error) {

      reject(error);
      return;

    }

  });

}


export {
  streamConnection,
  streamShop,
  streamProducts,
  streamCollects,
  streamSmartCollections,
  streamCustomCollections,
  streamOrders,
  streamCustomers
}
