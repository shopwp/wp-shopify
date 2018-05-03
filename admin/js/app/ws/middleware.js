import filter from 'lodash/filter';
import isEmpty from 'lodash/isEmpty';

import {
  getProductsCount,
  getCollectsCount,
  getSmartCollectionsCount,
  getCustomCollectionsCount,
  getOrdersCount,
  getCustomersCount,
  getWebhooksCount,
  getShopCount
} from '../ws/ws';

import {
  syncConnection,
  syncShop,
  syncProducts,
  syncCollects,
  syncSmartCollections,
  syncCustomCollections,
  syncOrders,
  syncCustomers,
  syncWebhooks,
  syncImageAlt
} from './syncing';

import {
  updateModalHeadingText,
  updateCurrentConnectionStepText
} from '../utils/utils-dom';

import {
  isWordPressError
} from '../utils/utils';

import {
  sanitizeErrorResponse,
  returnCustomError
} from '../utils/utils-data';


/*

Syncing Shopify data with WordPress CPT

*/
async function syncPluginData() {

  // 1. Smart Collections
  if (WP_Shopify.selective_sync.all || WP_Shopify.selective_sync.smart_collections) {

    try {
      await syncSmartCollections(); // wps_insert_smart_collections_data

    } catch(errors) {
      return returnCustomError(errors);

    }

  }

  // 2. Custom Collections
  if (WP_Shopify.selective_sync.all || WP_Shopify.selective_sync.custom_collections) {

    try {
      await syncCustomCollections(); // wps_insert_custom_collections_data

    } catch(errors) {
      return returnCustomError(errors);

    }

  }

  // 3. Remaining data
  try {

    var remainingResp = await Promise.all([
      syncConnection(), // wps_insert_connection
      syncShop(), // wps_insert_shop
      syncProducts(), // wps_insert_products_data
      syncCollects(), // wps_insert_collects
      /* @if NODE_ENV='pro' */
      syncOrders(), // wps_insert_orders
      syncCustomers(), // wps_insert_customers
      syncWebhooks()
      /* @endif */
    ]);

  } catch(errors) {
    return returnCustomError(errors);

  }

  return remainingResp;

}


/*

Syncing Shopify data with WordPress CPT

*/
function getItemCounts() {

  return new Promise(async function(resolve, reject) {

    try {

      var counts = await Promise.all([
        /* @if NODE_ENV='pro' */
        getWebhooksCount(), // wps_ws_get_webhooks_count
        getOrdersCount(), // wps_ws_get_orders_count
        getCustomersCount(), // wps_ws_get_customers_count
        /* @endif */
        getSmartCollectionsCount(), // wps_ws_get_smart_collections_count
        getCustomCollectionsCount(), // wps_ws_get_custom_collections_count
        getProductsCount(), // wps_ws_get_products_count
        getCollectsCount(), // wps_ws_get_collects_count
        getShopCount() // wps_ws_get_shop_count
      ]);

      if (!isEmpty(filter(counts, isWordPressError))) {
        reject(counts);

      } else {
        resolve(counts);
      }

    } catch(errors) {
      reject(errors);
    }

  });

}


export {
  syncPluginData,
  getItemCounts
}
