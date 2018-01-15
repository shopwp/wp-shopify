import filter from 'lodash/filter';
import isEmpty from 'lodash/isEmpty';

import {
  getProductsCount,
  getCollectsCount,
  getSmartCollectionsCount,
  getCustomCollectionsCount,
  getOrdersCount,
  getCustomersCount,
  getWebhooksCount
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
  try {
    await syncSmartCollections(); // wps_insert_smart_collections_data

  } catch(errors) {
    console.error('syncSmartCollections ERRRORS: ', errors);
    return returnCustomError(errors);

  }

  // 2. Custom Collections
  try {
    await syncCustomCollections(); // wps_insert_custom_collections_data

  } catch(errors) {
    console.error('syncCustomCollections ERRRORS: ', errors);
    return returnCustomError(errors);

  }


  // 3. Remaining data
  try {

    var remainingResp = await Promise.all([
      syncConnection(), // wps_insert_connection
      syncShop(), // wps_insert_shop
      syncProducts(), // wps_insert_products_data
      syncCollects(), // wps_insert_collects
      syncOrders(), // wps_insert_orders
      syncCustomers(), // wps_insert_customers
      syncWebhooks() // wps_ws_register_all_webhooks
    ]);

  } catch(errors) {
    console.error('Promise.all ERRRORS: ', errors);
    return returnCustomError(errors);

  }

  return remainingResp;



  // try {
  //   console.log('ABOUT TO TRY THIS syncImageAlt');
  //   var okok = await syncImageAlt();
  //   console.log("okok: ", okok);
  //
  // } catch (e) {
  //   console.log("e: ", e);
  // }

}


/*

Syncing Shopify data with WordPress CPT

*/
function getItemCounts() {

  return new Promise(async function(resolve, reject) {

    try {

      var counts = await Promise.all([
        getWebhooksCount(), // wps_ws_get_webhooks_count
        getSmartCollectionsCount(), // wps_ws_get_smart_collections_count
        getCustomCollectionsCount(), // wps_ws_get_custom_collections_count
        getProductsCount(), // wps_ws_get_products_count
        getCollectsCount(), // wps_ws_get_collects_count
        getOrdersCount(), // wps_ws_get_orders_count
        getCustomersCount() // wps_ws_get_customers_count
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
