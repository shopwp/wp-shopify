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
  isWordPressError,
  isTimeout
} from '../utils/utils';

import {
  emptyDataCount
} from '../utils/utils-data';

import {
  getMessageError
} from '../messages/messages';


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
        reject(connectionData);
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
        reject(connection);
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
        reject({ success: false, data: 'Syncing connection not found during getShopData'});
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
        reject({ success: false, data: 'Syncing connection not found during insertShopData'});
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

    var itemsToAdd = false;

    /*

    1. Get products count

    */
    try {

      var itemCount = await getTotalCountsFromSession(); // get_total_counts
      console.log("itemCount: ", itemCount);

      if (isWordPressError(itemCount)) {
        reject(itemCount);
        return;
      }

      if (!connectionInProgress()) {
        reject({ success: false, data: 'Syncing connection not found during getTotalCountsFromSession'});
        return;
      }

      itemCount = itemCount.data.products;

      if (emptyDataCount(itemCount)) {
        console.log('emptyDataCount ... resolving');
        resolve();
      }

    } catch(error) {
      reject(error);
      return;
    }


    /*

    2. Get all products

    */
    var { currentPage, pages, items } = constructStreamingOptions(itemCount);

    while (currentPage <= pages) {

      try {

        itemsToAdd = await insertProductsData(currentPage); // wps_insert_products_data

        console.log("itemsToAdd: ", itemsToAdd);

        // throw {
        //   status: 504,
        //   statusText: "Gateway Time-out"
        // }

        if (isWordPressError(itemsToAdd)) {
          reject(itemsToAdd);
          break;
        }

        if (!connectionInProgress()) {
          reject({ success: false, data: 'Syncing connection not found during insertProductsData'});
          break;
        }

        currentPage += 1;


      } catch (error) {

        console.error('RAW WP Shopify Error: ', error);

        reject( getMessageError(error) );
        break;

        // if ( isTimeout(error.status) ) {
        //   console.log('isTimeout ... ', error);
        //
        //   // This is an issue ... the program will conitnue syncing with the next page, potentially skipping a bunch of products
        //   currentPage += 1;
        //   continue;
        //
        // } else {
        //   console.log('In here??? ', error);
        //   reject();
        //   break;
        //
        // }

      }

    }

    resolve(itemsToAdd);
    return;

  });

}


/*

Stream Collects
Returns Collects

*/
async function streamCollects() {

  return new Promise(async function streamCollectsHandler(resolve, reject) {

    var itemsToAdd = false;

    /*

    1. Get collects count

    */
    try {

      var itemCount = await getTotalCountsFromSession(); // get_total_counts

      if (isWordPressError(itemCount)) {
        reject(itemCount);
        return;
      }

      if (!connectionInProgress()) {
        reject({ success: false, data: 'Syncing connection not found during getTotalCountsFromSession'});
        return;
      }

      itemCount = itemCount.data.collects;

      if (emptyDataCount(itemCount)) {
        resolve();
      }

    } catch(error) {
      reject(error);
      return;
    }


    /*

    2. Insert all collects

    */
    var { currentPage, pages, items } = constructStreamingOptions(itemCount);

    // Runs for each page of collects
    while (currentPage <= pages) {

      try {

        itemsToAdd = await insertCollects(currentPage); // wps_insert_collects

        console.log("itemsToAdd::::::::::", itemsToAdd);

        if (isWordPressError(itemsToAdd)) {
          reject(itemsToAdd);
          break;
        }

        if (!connectionInProgress()) {
          reject({ success: false, data: 'Syncing connection not found during insertCollects'});
          break;
        }

        currentPage += 1;

      } catch(error) {

        console.error('WP Shopify insertCollects Error: ', error);
        reject(error);
        break;

        // if ( isTimeout(error.status) ) {
        //
        //   currentPage += 1;
        //   continue;
        //
        // } else {
        //
        //   reject(error);
        //   break;
        //
        // }

      }

    }

    resolve(itemsToAdd);
    return;


  });

}


/*

Stream Smart Collections
Returns Smart Collections

*/
function streamSmartCollections() {

  return new Promise(async function streamSmartCollectionsHandler(resolve, reject) {

    var itemsToAdd = false;

    /*

    1. Get Smart Collections Count

    */
    try {

      var itemCount = await getTotalCountsFromSession(); // get_total_counts

      if (isWordPressError(itemCount)) {
        reject(itemCount);
        return;
      }

      if (!connectionInProgress()) {
        reject({ success: false, data: 'Syncing connection not found during getTotalCountsFromSession'});
        return;
      }

      itemCount = itemCount.data.smart_collections;

      if (emptyDataCount(itemCount)) {
        resolve();
      }

    } catch(error) {

      reject(error);
      return;

    }


    /*

    2. Insert all Smart Collections

    */
    var { currentPage, pages, items } = constructStreamingOptions(itemCount);

    while (currentPage <= pages) {

      try {

        itemsToAdd = await insertSmartCollections(currentPage); // wps_insert_smart_collections_data

        if (isWordPressError(itemsToAdd)) {
          reject(itemsToAdd);
          break;
        }

        if (!connectionInProgress()) {
          reject({ success: false, data: 'Syncing connection not found during insertSmartCollections'});
          break;
        }

        currentPage += 1;


      } catch(error) {

        console.error('WP Shopify insertSmartCollections Error: ', error);

        if ( isTimeout(error.status) ) {
          currentPage += 1;
          continue;

        } else {

          reject(error);
          break;

        }

      }

    }

    resolve(itemsToAdd);
    return;


  });

}


/*

Stream Custom Collections
Returns Smart Collections

*/
async function streamCustomCollections() {

  return new Promise(async function streamCustomCollectionsHandler(resolve, reject) {

    var itemsToAdd = false;

    /*

    1. Get Smart Collections Count

    */
    try {

      var itemCount = await getTotalCountsFromSession(); // get_total_counts

      if (isWordPressError(itemCount)) {
        reject(itemCount);
        return;
      }

      if (!connectionInProgress()) {
        reject({ success: false, data: 'Syncing connection not found during getTotalCountsFromSession'});
        return;
      }

      itemCount = itemCount.data.custom_collections;

      if (emptyDataCount(itemCount)) {
        resolve();
      }

    } catch(error) {
      reject(error);
      return;
    }


    /*

    2. Insert all Smart Collections

    */

    var { currentPage, pages, items } = constructStreamingOptions(itemCount);

    while (currentPage <= pages) {

      try {

        itemsToAdd = await insertCustomCollections(currentPage); // wps_insert_custom_collections_data

        if (isWordPressError(itemsToAdd)) {
          reject(itemsToAdd);
          break;
        }

        if (!connectionInProgress()) {
          reject({ success: false, data: 'Syncing connection not found during insertCustomCollections'});
          break;
        }

        currentPage += 1;


      } catch(error) {

        console.error('WP Shopify insertCustomCollections Error: ', error);

        if ( isTimeout(error.status) ) {
          currentPage += 1;
          continue;

        } else {

          reject(error);
          break;

        }

      }

    }

    resolve(itemsToAdd);
    return;


  });

}


/*

Stream Orders
Returns Orders

*/
async function streamOrders() {

  return new Promise(async function streamOrdersHandler(resolve, reject) {

    var itemsToAdd;

    /*

    Step 1. Get Orders count

    */
    try {

      var itemCount = await getTotalCountsFromSession(); // get_total_counts

      if (isWordPressError(itemCount)) {
        reject(itemCount);
        return;
      }

      if (!connectionInProgress()) {
        reject({ success: false, data: 'Syncing connection not found during getTotalCountsFromSession'});
        return;
      }

      itemCount = itemCount.data.orders;

      if (emptyDataCount(itemCount)) {
        resolve();
      }

    } catch(error) {

      reject(error);
      return;

    }


    /*

    Step 2. Insert Orders

    */
    var { currentPage, pages, items } = constructStreamingOptions(itemCount);

    while (currentPage <= pages) {

      try {

        itemsToAdd = await insertOrders(currentPage); // wps_insert_orders

        if (isWordPressError(itemsToAdd)) {
          reject(itemsToAdd);
          break;
        }

        if (!connectionInProgress()) {
          reject({ success: false, data: 'Syncing connection not found during insertOrders'});
          break;
        }

        currentPage += 1;

      } catch(error) {

        console.error('WP Shopify insertOrders Error: ', error);

        if ( isTimeout(error.status) ) {
          currentPage += 1;
          continue;

        } else {

          reject(error);
          break;

        }

      }

    }

    resolve(itemsToAdd);
    return;


  });

}


/*

Stream Customers
Returns Customers

TODO: Combine with streamCustomers into a more generalized function

*/
async function streamCustomers() {

  return new Promise(async function streamCustomersHandler(resolve, reject) {

    var itemsToAdd = false;

    /*

    Step 1. Get Customers count

    */
    try {

      var itemCount = await getTotalCountsFromSession(); // get_total_counts

      if (isWordPressError(itemCount)) {
        reject(itemCount);
        return;
      }

      if (!connectionInProgress()) {
        reject({ success: false, data: 'Syncing connection not found during getTotalCountsFromSession'});
        return;
      }

      itemCount = itemCount.data.customers;

      if (emptyDataCount(itemCount)) {
        resolve();
      }

    } catch(error) {
      reject(error);
      return;
    }


    /*

    Step 2. Insert Customers

    */
    var { currentPage, pages, items } = constructStreamingOptions(itemCount);

    while (currentPage <= pages) {

      try {

        itemsToAdd = await insertCustomers(currentPage); // wps_insert_customers

        if (isWordPressError(itemsToAdd)) {
          reject(itemsToAdd);
          break;
        }

        if (!connectionInProgress()) {
          reject({ success: false, data: 'Syncing connection not found during insertCustomers'});
          break;
        }

        currentPage += 1;

      } catch(error) {

        console.error('WP Shopify insertCustomers Error: ', error);

        if ( isTimeout(error.status) ) {
          currentPage += 1;
          continue;

        } else {

          reject(error);
          break;

        }

      }

    }

    resolve(itemsToAdd);
    return;


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
