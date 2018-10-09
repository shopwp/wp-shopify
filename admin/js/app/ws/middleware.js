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
  getShopCount,
  setDataRelationships
} from '../ws/ws';

import {
  syncShop,
  syncProducts,
  syncSmartCollections,
  syncCustomCollections,
  syncOrders,
  syncCustomers,
  syncWebhooks,
} from './syncing';

import {
  streamProducts,
  streamCollects,
  streamOrders,
  streamCustomers,
  streamSmartCollections,
  streamCustomCollections
} from './streaming';

import {
  updateModalHeadingText,
  updateCurrentConnectionStepText
} from '../utils/utils-dom';

import {
  convertArrayWrapToObject
} from '../utils/utils';

import {
  sanitizeErrorResponse,
  returnCustomError
} from '../utils/utils-data';

import {
  syncCollectsAndProducts
} from '../tools/tools';


/*

Syncing Shopify data with WordPress CPT

Each Promise here loops through the counted number of items and kicks off
the batch process

*/
async function syncPluginData(counts, inital = false) {

  WP_Shopify.isSyncing = true;

  counts = convertArrayWrapToObject(counts);

  var promises = [
    streamSmartCollections(counts.smart_collections),
    streamCustomCollections(counts.custom_collections),
    syncShop(), // insert_shop
    streamProducts(counts.products),
    streamCollects(counts.collects),
  ];


  return Promise.all(promises);

}


/*

Syncing Shopify data with WordPress CPT

*/
function getItemCounts() {

  return Promise.all([


    getSmartCollectionsCount(),
    getCustomCollectionsCount(),
    getProductsCount(),
    getCollectsCount(),
    getShopCount()

  ]);

}


export {
  syncPluginData,
  getItemCounts
}
