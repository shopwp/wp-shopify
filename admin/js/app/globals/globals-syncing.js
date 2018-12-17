import toInteger from 'lodash/toInteger';
import { toBoolean } from '../utils/utils';


/*

Is Syncing Everything

*/
const isSyncingAll = () => toBoolean(WP_Shopify.selective_sync.all);


/*

Is Syncing Products

*/
const isSyncingProducts = () => toBoolean( isSyncingAll() || WP_Shopify.selective_sync.products );


/*

Is Syncing Collects

*/
const isSyncingCollects = () => toBoolean( isSyncingAll() || WP_Shopify.selective_sync.products );


/*

Is Syncing Orders

*/
const isSyncingOrders = () => toBoolean( isSyncingAll() || WP_Shopify.selective_sync.orders );


/*

Is Syncing Customers

*/
const isSyncingCustomers = () => toBoolean( isSyncingAll() || WP_Shopify.selective_sync.customers );


/*

Is Syncing Collections

*/
const isSyncingCollections = () => toBoolean( isSyncingAll() || WP_Shopify.selective_sync.custom_collections || WP_Shopify.selective_sync.smart_collections );


/*

Is Syncing Smart Collections

*/
const isSyncingSmartCollections = () => toBoolean( isSyncingCollections() );


/*

Is Syncing Custom Collections

*/
const isSyncingCustomCollections = () => toBoolean( isSyncingCollections() );


/*

Is Syncing Shop

*/
const isSyncingShop = () => toBoolean( isSyncingAll() || WP_Shopify.selective_sync.shop );


/*

Is Connecting

*/
const isConnecting = () => toBoolean( WP_Shopify.isConnecting );


/*

Is Reconnecting Webhooks

*/
const isReconnectingWebhooks = () => toBoolean( WP_Shopify.reconnectingWebhooks );


/*

Gets the selective sync choices

*/
const getSelectiveSync = () => WP_Shopify.selective_sync;


export {
  isSyncingAll,
  isSyncingProducts,
  isSyncingCollects,
  isSyncingOrders,
  isSyncingCustomers,
  isSyncingCollections,
  isSyncingSmartCollections,
  isSyncingCustomCollections,
  isSyncingShop,
  isConnecting,
  isReconnectingWebhooks,
  getSelectiveSync
}
