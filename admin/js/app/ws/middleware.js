import filter from 'lodash/filter';
import concat from 'lodash/concat';
import isEmpty from 'lodash/isEmpty';

import {
  getSmartCollectionsCount,
  getSmartCollections,
  getCustomCollectionsCount,
  getCustomCollections
} from './api/api-collections';

import {
  getShop,
  getShopCount
} from './api/api-shop';

import {
  getProducts,
  getProductsCount,
} from './api/api-products';

import {
  getCollects,
  getCollectsCount,
} from './api/api-collects';

import {
  getOrders,
  getOrdersCount,
} from './api/api-orders';

import {
  getCustomers,
  getCustomersCount,
} from './api/api-customers';

import {
  registerWebhooks,
  getWebhooksCount
} from './api/api-webhooks';

import {
  streamItems
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



import {
  isSyncingProducts,
  isSyncingCollects,
  isSyncingOrders,
  isSyncingCustomers,
  isSyncingSmartCollections,
  isSyncingCustomCollections,
  isSyncingCollections,
  isSyncingShop
} from '../globals/globals-syncing';






/*

Syncing Shopify data with WordPress CPT

Each Promise here loops through the counted number of items and kicks off
the batch process

*/
function syncPluginData(counts, inital = false) {

  counts = convertArrayWrapToObject(counts);

  var promises = [];

  if ( isSyncingCollections() ) {
    promises = concat(promises, streamItems(counts.smart_collections, getSmartCollections) );
    promises = concat(promises, streamItems(counts.custom_collections, getCustomCollections) );
  }

  if ( isSyncingShop() ) {
    promises = concat(promises, streamItems(counts.shop, getShop) );
  }

  if ( isSyncingProducts() ) {
    promises = concat(promises, streamItems(counts.products, getProducts) );
  }

  if ( isSyncingCollects() ) {
    promises = concat(promises, streamItems(counts.collects, getCollects) );
  }



  return Promise.all(promises);

}


/*

Syncing Shopify data with WordPress CPT

*/
function getItemCounts() {

  var promises = [];

  if ( isSyncingCollections() ) {
    promises = concat(promises, getSmartCollectionsCount() );
    promises = concat(promises, getCustomCollectionsCount() );
  }

  if ( isSyncingShop() ) {
    promises = concat(promises, getShopCount() );
  }

  if ( isSyncingProducts() ) {
    promises = concat(promises, getProductsCount() );
  }

  if ( isSyncingCollects() ) {
    promises = concat(promises, getCollectsCount() );
  }


  return Promise.all(promises);

}


export {
  syncPluginData,
  getItemCounts
}
