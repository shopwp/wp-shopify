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
  setConnectionStepMessage
} from '../connect/connect';

import {
  updateModalHeadingText,
  updateCurrentConnectionStepText
} from '../utils/utils-dom';

import {
  sanitizeErrorResponse
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
    return new Error(errors);

  }

  // 2. Custom Collections
  try {
    await syncCustomCollections(); // wps_insert_custom_collections_data

  } catch(errors) {
    console.error('syncCustomCollections ERRRORS: ', errors);
    return new Error(errors);

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

    return new Error(sanitizeErrorResponse(errors));

  }

  console.log("remainingResp ", remainingResp);

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


export {
  syncPluginData
}
