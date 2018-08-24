import {
  mergeNewDataIntoCurrent,
  convertAuthDataToString,
  getErrorContents
} from '../utils/utils-data';

import {
  isTimeout,
  findStatusCodeFirstNum
} from '../utils/utils';

import {
  getWebhooksReconnect
} from './localstorage';


/*

Removing all data

*/
function deleteAllData() {

  return new Promise((resolve, reject) => {

    const action_name = 'delete_all_data';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get all products from Shopify
Returns: Promise

*/
function getProductsCount() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_products_count';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get all products from Shopify
Returns: Promise

*/
function cacheNoticeDismissal(dismiss_name) {

  return new Promise((resolve, reject) => {

    const action_name = 'cache_admin_notice_dismissal';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce,
        dismiss_name: dismiss_name
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Syncs Alt Text
Returns: Promise

*/
function insertAltText() {

  return new Promise((resolve, reject) => {

    const action_name = 'insert_alt_text';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get all Collects from Shopify
Returns: Promise

*/
function getCollectsCount() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_collects_count';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get Smart Collections Count
Returns: Promise

*/
function getSmartCollectionsCount() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_smart_collections_count';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get Custom Collections Count
Returns: Promise

*/
function getCustomCollectionsCount() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_custom_collections_count';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get Order Count
Returns: Promise

*/
function getOrdersCount() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_orders_count';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get Customers Count
Returns: Promise

*/
function getCustomersCount() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_customers_count';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get Shop Count
Returns: Promise

*/
function getShopCount() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_shop_count';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get Webhooks Count
Returns: Promise

*/
function getWebhooksCount() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_webhooks_count';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get all products from Shopify
Returns: Promise

*/
function getProductsFromCollection(collection) {

  return new Promise((resolve, reject) => {

    const action_name = 'get_products_from_collection';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        collectionID: collection.collectionId,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get all products from Shopify
Returns: Promise

*/
function getCollectsFromCollection(collectionID) {

  return new Promise((resolve, reject) => {

    const action_name = 'get_collects_from_collection';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        collectionID: collectionID,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get all products from Shopify
Returns: Promise

*/
function getSingleCollection(collectionID) {

  return new Promise((resolve, reject) => {

    const action_name = 'get_single_collection';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        collectionID: collectionID,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Inserting Shop data

*/
function insertShopData(shopData) {

  return new Promise((resolve, reject) => {

    const action_name = 'insert_shop';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        shopData: shopData,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Sending collections to server

*/
function saveConnectionData(connectionData) {

  return new Promise((resolve, reject) => {

    const action_name = 'save_connection';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        connectionData: connectionData,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Sending collections to server

*/
function removeConnectionData() {

  return new Promise((resolve, reject) => {

    const action_name = 'remove_connection';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Sending collections to server

*/
function getConnectionData() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_connection';

    jQuery.ajax({
      method: 'GET',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Insert Products Data

*/
function insertProductsData(currentPage = false) {

  return new Promise((resolve, reject) => {

    const action_name = 'insert_products';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        currentPage: currentPage,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get Webhooks

*/
function getWebhooks() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_webhooks';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

EDD - Get License Key Info
Returns Promise

*/
function getProductInfo(key) {

  return new Promise((resolve, reject) => {

    const action_name = 'edd_action=get_version';

    jQuery.ajax({
      type: 'GET',
      url: 'https://wpshop.io/edd-sl?edd_action=get_version&item_name=WP+Shopify&license=' + key + '&url=' + WP_Shopify.siteUrl,
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

EDD - Check License Key Validity
Returns Promise

*/
function getLicenseKeyStatus(key) {

  return new Promise((resolve, reject) => {

    const action_name = 'edd_action=check_license';

    jQuery.ajax({
      type: 'GET',
      url: 'https://wpshop.io/edd-sl?edd_action=check_license&item_name=WP+Shopify&license=' + key + '&url=' + WP_Shopify.siteUrl,
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

EDD - Activate License Key
Returns Promise

*/
function activateLicenseKey(key) {

  return new Promise((resolve, reject) => {

    const action_name = 'edd_action=activate_license';

    jQuery.ajax({
      type: 'GET',
      url: 'https://wpshop.io/edd-sl?edd_action=activate_license&item_name=WP+Shopify&license=' + key + '&url=' + WP_Shopify.siteUrl,
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

EDD - Deactivate License Key
Returns Promise

*/
function deactivateLicenseKey(key) {

  return new Promise((resolve, reject) => {

    const action_name = 'edd_action=deactivate_license';

    var url = 'https://wpshop.io/edd-sl?edd_action=deactivate_license&item_name=WP+Shopify&license=' + key + '&url=' + WP_Shopify.siteUrl;

    jQuery.ajax({
      type: 'GET',
      url: url,
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Saving License Key
Returns Promise

*/
function saveLicenseKey(data) {

  return new Promise((resolve, reject) => {

    const action_name = 'license_save';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce,
        data: data
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Deleting License Key
Returns Promise

*/
function deleteLicenseKey(key) {

  return new Promise((resolve, reject) => {

    const action_name = 'license_delete';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      data: {
        action: action_name,
        key: key,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get License Key
Returns Promise

*/
function getLicenseKey() {

  return new Promise((resolve, reject) => {

    const action_name = 'license_get';

    jQuery.ajax({
      method: 'GET',
      url: WP_Shopify.ajax,
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Attach all Webhooks
Returns: Promise

*/
function registerWebhooks(removalErrors) {

  return new Promise((resolve, reject) => {

    const action_name = 'register_all_webhooks';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce,
        removalErrors: removalErrors,
        webhooksReconnect: getWebhooksReconnect()
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Send uninstall request to Shopify
Returns Promise

*/
function deleteOnlySyncedData() {

  return new Promise((resolve, reject) => {

    const action_name = 'delete_only_synced_data';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Send uninstall request to Shopify
Returns Promise

*/
function deletePostsAndSyncedData() {

  return new Promise((resolve, reject) => {

    const action_name = 'delete_posts_and_synced_data';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Deletes product and collection posts only
Returns Promise

*/
function deleteOnlyPostData() {

  return new Promise((resolve, reject) => {

    const action_name = 'delete_only_posts';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Set the plugin settings on consumer side. This
allows us to save the plugin settings via AJAX

Returns: Promise

*/
function setPluginSettings(formData) {

  return new Promise((resolve, reject) => {

    const action_name = 'general_settings_form';

    jQuery.ajax({
      method: "POST",
      url: "options.php",
      data: {
        nonce: WP_Shopify.nonce,
        formData: formData
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Del Webhooks

*/
function delWebhooks() {

  return new Promise((resolve, reject) => {

    const action_name = 'wps_ws_delete_webhook';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get Progress Count

*/
function getProgressCount() {

  return new Promise((resolve, reject) => {

    const action_name = 'progress_status';

    jQuery.ajax({
      method: 'GET',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

updateSettings
Returns: Promise

*/
function updateSettings(options) {

  return new Promise((resolve, reject) => {

    const action_name = 'update_settings_general';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce,
        data: options
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get plugin settings from consumer
Returns: Promise

*/
function getShopData() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_shop';

    jQuery.ajax({
      method: 'GET',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Set Syncing Indicator

*/
function setSyncingIndicator(syncing) {

  return new Promise((resolve, reject) => {

    const action_name = 'set_syncing_indicator';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      data: {
        action: action_name,
        syncing: syncing,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Clear Cache

*/
function clearCache() {

  return new Promise((resolve, reject) => {

    const action_name = 'clear_cache';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Insert Orders

*/
function insertOrders(currentPage = false) {

  return new Promise((resolve, reject) => {

    const action_name = 'insert_orders';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        currentPage: currentPage,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Insert Customers

*/
function insertCustomers(currentPage = false) {

  return new Promise((resolve, reject) => {

    const action_name = 'insert_customers';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        currentPage: currentPage,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Insert Customers

*/
function startProgress(progress) {

  return new Promise((resolve, reject) => {

    const action_name = 'wps_progress_bar_start';

    jQuery.ajax({
      method: 'GET',
      url: WP_Shopify.ajax,
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Progress Session Start

*/
function progressSessionStart(resync = false, includes = [], excludes = []) {

  return new Promise((resolve, reject) => {

    const action_name = 'progress_session_create';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce,
        resync: resync,
        includes: includes,
        excludes: excludes
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Remove Webhooks

*/
function removeWebhooks() {

  return new Promise((resolve, reject) => {

    const action_name = 'delete_webhooks';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Remove Webhooks

*/
function saveCountsToSession(counts, exclusions = false) {

  return new Promise((resolve, reject) => {

    const action_name = 'insert_syncing_totals';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce,
        counts: counts,
        exclusions: exclusions
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Get Smart Collections Count
Returns: Promise

*/
function getTotalCountsFromSession() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_syncing_totals';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function getBulkCollects(currentPage) {

  return new Promise((resolve, reject) => {

    const action_name = 'get_bulk_collects';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce,
        currentPage: currentPage
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function getBulkProducts(currentPage) {

  return new Promise((resolve, reject) => {

    const action_name = 'get_bulk_products';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce,
        currentPage: currentPage
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function getBulkOrders(currentPage) {

  return new Promise((resolve, reject) => {

    const action_name = 'get_bulk_orders';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce,
        currentPage: currentPage
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function getBulkCustomers(currentPage) {

  return new Promise((resolve, reject) => {

    const action_name = 'get_bulk_customers';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce,
        currentPage: currentPage
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function getBulkSmartCollections(currentPage) {

  return new Promise((resolve, reject) => {

    const action_name = 'get_bulk_smart_collections';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce,
        currentPage: currentPage
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function getBulkCustomCollections(currentPage) {

  return new Promise((resolve, reject) => {

    const action_name = 'get_bulk_custom_collections';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce,
        currentPage: currentPage
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}

function getSyncNotices() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_syncing_notices';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function killSyncing() {

  return new Promise((resolve, reject) => {

    const action_name = 'kill_syncing';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function setProductPostsRelationships() {

  return new Promise((resolve, reject) => {

    const action_name = 'set_product_posts_relationships';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function setCollectionPostsRelationships() {

  return new Promise((resolve, reject) => {

    const action_name = 'set_collection_posts_relationships';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function getWebhooksRemovalStatus() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_webhooks_removal_status';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function getDataRemovalStatus() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_data_removal_status';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function getPostsRelationshipsStatus() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_posts_relationships_status';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function getAllCollections() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_all_collections';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function getSelectedCollections() {

  return new Promise((resolve, reject) => {

    const action_name = 'get_selected_collections';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function checkForActiveConnection() {

  return new Promise((resolve, reject) => {

    const action_name = 'check_connection';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function checkForValidServerConnection() {

  return new Promise((resolve, reject) => {

    const action_name = 'check_valid_server_connection';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


function resetNoticeFlags() {

  return new Promise((resolve, reject) => {

    const action_name = 'reset_notice_flags';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Kicks off the table migration process

*/
function migrateTables() {

  return new Promise( (resolve, reject) => {

    const action_name = 'run_table_migration_' + WP_Shopify.latestVersionCombined;

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


export {
  getProductsFromCollection,
  getCollectsFromCollection,
  insertProductsData,
  getWebhooks,
  setPluginSettings,
  delWebhooks,
  getSingleCollection,
  deactivateLicenseKey,
  activateLicenseKey,
  getLicenseKeyStatus,
  getProductInfo,
  saveLicenseKey,
  deleteLicenseKey,
  getLicenseKey,
  getProductsCount,
  getCollectsCount,
  updateSettings,
  getShopData,
  insertShopData,
  saveConnectionData,
  getConnectionData,
  deleteOnlySyncedData,
  setSyncingIndicator,
  clearCache,
  insertOrders,
  getOrdersCount,
  insertCustomers,
  getCustomersCount,
  startProgress,
  registerWebhooks,
  deleteAllData,
  insertAltText,
  getProgressCount,
  getSmartCollectionsCount,
  getCustomCollectionsCount,
  progressSessionStart,
  removeWebhooks,
  saveCountsToSession,
  getTotalCountsFromSession,
  removeConnectionData,
  getWebhooksCount,
  getShopCount,
  cacheNoticeDismissal,
  getBulkCollects,
  getBulkProducts,
  getBulkOrders,
  getBulkCustomers,
  getBulkSmartCollections,
  getBulkCustomCollections,
  getSyncNotices,
  setProductPostsRelationships,
  setCollectionPostsRelationships,
  getWebhooksRemovalStatus,
  getDataRemovalStatus,
  getPostsRelationshipsStatus,
  killSyncing,
  getAllCollections,
  getSelectedCollections,
  checkForActiveConnection,
  resetNoticeFlags,
  deleteOnlyPostData,
  deletePostsAndSyncedData,
  checkForValidServerConnection,
  migrateTables
}
