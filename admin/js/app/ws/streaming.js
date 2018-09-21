import to from 'await-to-js';

import {
  getShopData,
  getTotalCountsFromSession,
  insertShopData,
  getBulkProducts,
  getBulkCollects,
  getBulkOrders,
  getBulkCustomers,
  getBulkSmartCollections,
  getBulkCustomCollections
} from '../ws/ws';

import {
  isWordPressError,
  isTimeout
} from '../utils/utils';

import {
  emptyDataCount
} from '../utils/utils-data';

import {
  cleanUpAfterSync
} from '../utils/utils-progress';

import {
  getMessageError
} from '../messages/messages';

import {
  syncingConfigJavascriptError,
  syncingConfigErrorBeforeSync
} from './syncing-config';


/*

Construct Streaming Options

itemCount = (int)

*/
function constructStreamingOptions(itemCount) {

  var pageSize = parseInt(WP_Shopify.itemsPerRequest);

  // Important to coerce ... can sometimes be a string
  itemCount = parseInt(itemCount);

  return {
    currentPage: 1,
    pages: Math.ceil(itemCount / pageSize),
    items: []
  }

}


/*

Stream Shop: get_shop, insert_shop

Calls Shopify at /admin/shop.json

Doesn't save error to DB -- returns to client instead

*/
async function streamShop() {

  return new Promise(async (resolve, reject) => {

    /*

    1. Get Shop Data from Shopify

    */
    var [shopError, shopData] = await to( getShopData() ); // get_shop

    if (shopError) {
      cleanUpAfterSync( syncingConfigJavascriptError(shopError) );
      reject();
      return;
    }

    if (isWordPressError(shopData)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync(shopData) );
      resolve();
      return;
    }

    shopData = shopData.data;


    /*

    2. Save shop data to plugin DB

    */
    var [insertShopError, insertShopDataResponse] = await to( insertShopData(shopData) ); // insert_shop

    if (insertShopError) {
      cleanUpAfterSync( syncingConfigJavascriptError(insertShopError) );
      reject();
      return;
    }

    if (isWordPressError(insertShopDataResponse)) {
      cleanUpAfterSync( syncingConfigErrorBeforeSync(insertShopDataResponse) );
      resolve();
      return;
    }

    // If everything went smoothly, return!
    resolve(insertShopDataResponse);


  });

}


/*

Stream Products: get_bulk_products

Begins the Products background syncing process

Returns error to client

*/
async function streamProducts(itemCount) {

  return new Promise( async function streamProductsHandler(resolve, reject) {

    if (!WP_Shopify.selective_sync.all && !WP_Shopify.selective_sync.products) {
      resolve();
      return;
    }














    var { currentPage, pages, items } = constructStreamingOptions(itemCount);
    var productsList = [];

    while (currentPage <= pages) {

      var [productsError, productsData] = await to( getBulkProducts(currentPage) ); // get_bulk_products

      if (productsError) {
        cleanUpAfterSync( syncingConfigJavascriptError(productsError) );
        resolve();
        break;
      }

      if (isWordPressError(productsData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(productsData) );
        resolve();
        break;
      }

      productsList.push(productsData);
      currentPage += 1;

    }

    // If everything went smoothly, return!
    resolve(productsList);


  });


}


/*

Stream Collects
- Begins the Collects background syncing process

*/
function streamCollects(itemCount) {

  return new Promise(async function streamCollectsHandler(resolve, reject) {

    if (!WP_Shopify.selective_sync.all && !WP_Shopify.selective_sync.products) {
      resolve();
      return;
    }


    var { currentPage, pages, items } = constructStreamingOptions(itemCount);
    var collectsList = [];

    while (currentPage <= pages) {

      var [collectsError, collectsData] = await to( getBulkCollects(currentPage) ); // get_bulk_collects

      if (collectsError) {
        cleanUpAfterSync( syncingConfigJavascriptError(collectsError) );
        resolve();
        break;
      }

      if (isWordPressError(collectsData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(collectsData) );
        resolve();
        break;
      }

      collectsList.push(collectsData);
      currentPage += 1;

    }

    // If everything went smoothly, return!
    resolve(collectsList);

  });

}


/*

Stream Orders: get_bulk_orders
- Begins the Orders background syncing process

*/
async function streamOrders(itemCount) {

  return new Promise(async function streamOrdersHandler(resolve, reject) {

    if (!WP_Shopify.selective_sync.all && !WP_Shopify.selective_sync.orders) {
      resolve();
      return;
    }


    var { currentPage, pages, items } = constructStreamingOptions(itemCount);
    var ordersList = [];

    while (currentPage <= pages) {

      var [ordersError, ordersData] = await to( getBulkOrders(currentPage) ); // get_bulk_orders

      if (ordersError) {
        cleanUpAfterSync( syncingConfigJavascriptError(ordersError) );
        resolve();
        break;
      }

      if (isWordPressError(ordersData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(ordersData) );
        resolve();
        break;
      }

      ordersList.push(ordersData);
      currentPage += 1;

    }

    resolve(ordersList);

  });

}


/*

Stream Customers: get_bulk_customers
- Begins the Customers background syncing process

*/
async function streamCustomers(itemCount) {

  return new Promise(async function streamCustomersHandler(resolve, reject) {

    if (!WP_Shopify.selective_sync.all && !WP_Shopify.selective_sync.customers) {
      resolve();
      return;
    }

    var { currentPage, pages, items } = constructStreamingOptions(itemCount);
    var customersList = [];


    while (currentPage <= pages) {

      var [customersError, customersData] = await to( getBulkCustomers(currentPage) ); // get_bulk_customers

      if (customersError) {
        cleanUpAfterSync( syncingConfigJavascriptError(customersError) );
        resolve();
        break;
      }

      if (isWordPressError(customersData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(customersData) );
        resolve();
        break;
      }

      customersList.push(customersData);
      currentPage += 1;

    }

    resolve(customersList);

  });

}


/*

Stream Smart Collections: get_bulk_smart_collections
Returns Smart Collections

*/
function streamSmartCollections(itemCount) {

  return new Promise(async function streamSmartCollectionsHandler(resolve, reject) {

    var { currentPage, pages, items } = constructStreamingOptions(itemCount);
    var collectionsList = [];

    while (currentPage <= pages) {

      var [smartCollectionsError, smartCollectionsData] = await to( getBulkSmartCollections(currentPage) ); // get_bulk_smart_collections

      if (smartCollectionsError) {
        cleanUpAfterSync( syncingConfigJavascriptError(smartCollectionsError) );
        resolve();
        break;
      }

      if (isWordPressError(smartCollectionsData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(smartCollectionsData) );
        resolve();
        break;
      }

      collectionsList.push(smartCollectionsData);
      currentPage += 1;

    }

    resolve(collectionsList);
    return;


  });

}


/*

Stream Custom Collections
Returns Smart Collections

*/
async function streamCustomCollections(itemCount) {

  return new Promise(async function streamCustomCollectionsHandler(resolve, reject) {

    var collectionsList = [];
    var { currentPage, pages, items } = constructStreamingOptions(itemCount);

    while (currentPage <= pages) {

      var [customCollectionsError, customCollectionsData] = await to( getBulkCustomCollections(currentPage) ); // get_bulk_custom_collections

      if (customCollectionsError) {
        cleanUpAfterSync( syncingConfigJavascriptError(customCollectionsError) );
        resolve();
        break;
      }

      if (isWordPressError(customCollectionsData)) {
        cleanUpAfterSync( syncingConfigErrorBeforeSync(customCollectionsData) );
        resolve();
        break;
      }

      collectionsList.push(customCollectionsData);
      currentPage += 1;

    }

    resolve(collectionsList);

  });

}


export {
  streamShop,
  streamProducts,
  streamCollects,
  streamSmartCollections,
  streamCustomCollections,
  streamOrders,
  streamCustomers,
  constructStreamingOptions
}
