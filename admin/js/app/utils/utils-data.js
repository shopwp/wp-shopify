import isEmpty from 'lodash/isEmpty';
import isError from 'lodash/isError';
import isEqual from 'lodash/isEqual';
import concat from 'lodash/concat';
import unionWith from 'lodash/unionWith';
import merge from 'lodash/merge';
import filter from 'lodash/filter';
import map from 'lodash/map';
import union from 'lodash/union';
import matches from 'lodash/matches';
import find from 'lodash/find';
import isArray from 'lodash/isArray';
import has from 'lodash/has';
import pickBy from 'lodash/pickBy';
import omitBy from 'lodash/omitBy';
import forEach from 'lodash/forEach';
import uniqBy from 'lodash/uniqBy';
import parseInt from 'lodash/parseInt';
import isNaN from 'lodash/isNaN';

import {
  getStartingURL
} from '../ws/localstorage';



/*

Returns a standarized format for client-side errors

*/
function getErrorContents(xhr, err, action_name) {

  return {
    statusCode: xhr.status,
    message: err,
    action_name: action_name
  }

}


function getRestErrorContents(error) {

  return {
    statusCode: error.status,
    message: error.data.message,
    action_name: error.data.code
  }

}


/*

Rejected Promise

*/
function rejectedPromise(reason) {

  return new Promise(function (resolve, reject) {
    reject(reason);
  });

}


/*

Get Product Images

*/
function getProductImages(product) {

  if (product !== undefined) {
    return product.images.map(function(image) {
      return image.src;
    });
  }

};


/*

Gets an array of collection IDs based on a product ID

*/
async function getCollectionIDs(collections) {
  return collections.map(function returnCollectionIDsHandler(collection) {
    return collection.collection_id;
  });
}


/*

* NEW *
Add collection IDs to products

*/
function mapCollectsToProducts(collects, products) {

  var productsWithCollections = products;

  jQuery.each(collects, function(index, collect) {

    jQuery.each(productsWithCollections, function(index, product) {

      if(product.productId === collect.product_id) {
        product.productCollection.push(collect.collection_id)
      }

    });

  });

  return productsWithCollections;

}


/*

* NEW *
Add collection IDs to products

*/
function mapCollectsToCollections(collects, collections) {

  var collectionsWithProducts = collections;

  jQuery.each(collects, function(index, collect) {

    jQuery.each(collectionsWithProducts, function(index, collection) {

      if(collection.collectionId === collect.collection_id) {
        collection.collectionProducts.push(collect.product_id)
      }

    });

  });

  return collectionsWithProducts;

}


/*

Set Collections Image
Returns: image src

*/
function setCollectionImage(collection) {

  if (collection.hasOwnProperty('image')) {
    return collection.image.src;
  }

};


/*

Merging new auth data into old
Returns: Array

*/
function mergeNewDataIntoCurrent(newAuthData, currentAuthData) {
  return unionWith(newAuthData, currentAuthData, isEqual);
}


/*

Converts JS value to string
Returns: String (authUserData === Object)

*/
function convertAuthDataToString(newAuthData) {
  return JSON.stringify(newAuthData);
}


/*

Adds matching collections to products object

*/
function addCollectionsToProduct(products, collects) {

  return products.map(function(product) {

    var finalCollectionsArray = [];

    collects.forEach(function(collect) {

      // If product ID matches collect ID
      if (product.productId === collect.product_id) {
        finalCollectionsArray = concat(product.productCollection, collect.productCollection);
      }

    });

    product.productCollection = finalCollectionsArray;

    return product;

  });

}


/*

Returns data property of WordPress reponse

*/
function sanitizeErrorResponse(error) {

  if (error.hasOwnProperty('data')) {
    return error.data;

  } else {
    return error;

  }

}


/*

Control promise

*/
function returnCustomError(errorMsg) {

  return {
    success: false,
    data: errorMsg
  }

}


/*

Returns the default exit options

*/
function getDefaultExitOptions() {

  return {
    headingText: 'Canceled',
    stepText: 'Stopped syncing',
    status: 'is-disconnected',
    buttonText: 'Close WP Shopify Sync',
    noticeList: [{
      type: 'warning',
      message: 'The syncing process was manually canceled early.'
    }],
    errorCode: '',
    clearInputs: true,
    noticeType: 'warning'
  }

}


/*

Produces a final object of all the config options for the DOM

*/
function getCombinedExitOptions(customOptions) {
  return merge(getDefaultExitOptions(), customOptions);
}


/*

Only Failed Requests

*/
function onlyFailedRequests(request) {

  if (request) {
    return !request.success;
  }

}


/*

Filters for only erors

*/
function filterForErrors(maybeErrors) {
  return filter(maybeErrors, onlyFailedRequests);
}


/*

Filters for only erors

*/
function pickFirstError(errors) {

  if (isArray(errors)) {
    return errors[0];
  }

  return errors;

}


/*

If a Promise all returns multiple errors, only pick one

*/
function returnOnlyFirstError(maybeErrors) {
  return pickFirstError( filterForErrors(maybeErrors) );
}


/*

Return Only Failed Requests

*/
function returnOnlyFailedRequests(noticeList) {

  if (!noticeList) {
    return [];
  }

  if (noticeList.hasOwnProperty('statusText')) {
    return [{
      'success': false,
      'type': 'error',
      'message': noticeList.status + ' error',
    }];
  }


  if (isError(noticeList)) {

    return [{
      'success': false,
      'type': 'error',
      'message': noticeList,
    }];

  } else {

    return map( filterForErrors(noticeList), sanitizeErrorResponse);

  }

}


/*

Only Warnings

*/
function onlyWarnings(notice) {

  if (notice) {
    return notice.type === 'warning';
  }

}


/*

Only non notice

*/
function onlyNonNotice(obj) {

  if (obj) {
    return !find([obj], 'type');
  }

}


/*

Runs for each sync type: products, webhooks, etc
{webhooks: 27}
{orders: 55}

*/
function onlyAvailableSyncOptions(obj) {


  // If syncing everything, return full list
  if (WP_Shopify.selective_sync.all) {
    return obj;
  }

  // If initial connect, or syncing webhooks, return webhooks
  if (WP_Shopify.isConnecting || WP_Shopify.reconnectingWebhooks) {
    if (obj.hasOwnProperty('webhooks')) {
      return obj;
    }
  }



  var onlySelectedSyncs = filterOutDeselectedSyncs(WP_Shopify.selective_sync);
  var nameOfSync = Object.getOwnPropertyNames(obj)[0];


  /*

  Falls into this conditional when the loop comes across the name of a selected sync

  */
  if (onlySelectedSyncs.hasOwnProperty(nameOfSync)) {
    return obj;

  } else {

    /*

    Falls into this conditional when the loop doesn't find the name of a selected sync.

    At this point we still need to check for the collects when the user has selected
    to sync only products.

    */
    if (has(onlySelectedSyncs, 'products')) {

      if ( has(obj, 'collects') ) {
        return obj;
      }

    }

  }


}




/*

Checks if user is attempting to sync products

*/
function isSyncingProducts() {

  if (WP_Shopify.reconnectingWebhooks) {
    return false;
  }

  if (WP_Shopify.selective_sync.all) {
    return true;

  } else if (WP_Shopify.selective_sync.products) {
    return true;

  } else {
    return false;
  }

}


/*

Checks if user is attempting to sync collections

*/
function isSyncingCollections() {

  if (WP_Shopify.reconnectingWebhooks) {
    return false;
  }

  if (WP_Shopify.selective_sync.all) {
    return true;

  } else if (WP_Shopify.selective_sync.custom_collections) {
    return true;

  } else if (WP_Shopify.selective_sync.smart_collections) {
    return true;

  } else {
    return false;
  }

}


/*

Checks if user is attempting to sync collections

*/
function hasConnection() {

  if (WP_Shopify.hasConnection === undefined) {
    return false;
  }

  if (WP_Shopify.hasConnection === true) {
    return true;

  } else if (WP_Shopify.hasConnection === 1) {
    return true;

  } else if (WP_Shopify.hasConnection === '1') {
    return true;

  } else {
    return false;
  }

}


/*

Filter Out Any Deselected Selective Syncs

Only returns selected syncs

*/
function filterOutDeselectedSyncs(syncs) {
  return pickBy(syncs, (value, key) => value == 1);
}


/*

Filter Out Any Deselected Selective Syncs

*/
function filterOutEmptySets(syncs) {

  return filter(syncs, (set) => {
    return !isEmpty( omitBy(set, (value, key) => emptyDataCount(value) ));
  });

}


/*

Filter Out Any Notice

*/
function filterOutAnyNotice(array) {
  return filter(array, onlyNonNotice);
}


/*

Filter Out Any Notice

*/
function filterOutSelectiveSync(array) {
  return filter(array, onlyAvailableSyncOptions);
}


/*

Filter Out Any Notice

*/
function filterOutSelectedDataForSync(arrayOfSyncObjects, exclusions) {

  return filter(arrayOfSyncObjects, function(syncObject) {

    var found = false;

    forEach(exclusions, function(exclusionName) {

      if (has(syncObject, exclusionName)) {
        found = true;
      }

    });

    return !found;

  });

}


/*

Return only warning notices

*/
function returnOnlyWarningNotices(noticeList) {
  return filter(extractNoticeData(noticeList, extractNoticeData), onlyWarnings);
}


/*

Only notices with a data property

*/
function onlyData(notice) {
  return notice.data;
}


/*

Extract notice data

*/
function extractNoticeData(noticeList) {
  return map(noticeList, onlyData);
}


/*

Create empty warning list

*/
function createEmptyWarningList() {
  return [];
}


/*

Add to current warning list

*/
function addToCurrentWarningList(currentWarningList, warning) {

  if (warning !== undefined && has(warning, 'data')) {

    if (find([warning.data], { 'type': 'warning'} )) {

      if (isArray(warning.data.message) && warning.data.message.length > 1) {

        warning.data.message.forEach(message => {
          currentWarningList.push({
            type: 'warning',
            message: [message]
          });
        });

      } else {
        currentWarningList.push(warning.data);
      }

    }

  }

  return currentWarningList;

}


/*

Add to warning list

*/
function addToWarningList(currentWarningList, newWarning) {

  var currentWarningListClone = currentWarningList;

  if (isArray(newWarning)) {

    newWarning.forEach( obj => {

      currentWarningListClone = addToCurrentWarningList(currentWarningListClone, obj);

    });

  } else {

    currentWarningListClone = addToCurrentWarningList(currentWarningListClone, newWarning);

  }

  return currentWarningListClone;

}


function setConnectionStatus(status) {
  WP_Shopify.hasConnection = status;
}

function getConnectionStatus() {

  if (hasConnection()) {
    return 'is-connected';

  } else {
    return 'is-disconnected';
  }

}


/*

Add Success Notice

*/
function addSuccessNotice() {

  if (hasConnection()) {
    return [{
      type: 'success',
      message: 'Success! You\'ve finished syncing your Shopify store with WordPress.'
    }];

  } else {
    return [{
      type: 'success',
      message: 'Success! You\'ve finished disconnecting your Shopify store from WordPress.'
    }];
  }

}



function hasErrorsInNoticeList(anyWarnings) {

  var hasErrors = false;

  forEach(anyWarnings, function(error) {

    if (error.type === 'error') {
      hasErrors = true;
    }

  });

  return hasErrors;

}


/*

Construct Final Notice List

*/
function constructFinalNoticeList(anyWarnings) {

  if (hasErrorsInNoticeList(anyWarnings)) {
    return uniqBy(union(anyWarnings), 'message');

  } else {
    return union(anyWarnings, addSuccessNotice());
  }

}


/*

Checks if count returns nothing

*/
function emptyDataCount(count) {

  if (count === 0 || count === '0') {
    return true;

  } else {
    return false;
  }

}


/*

Used for testing / figuring out memory usage

*/
function formatBytes(a, b) {

  if (0 == a) return "0 Bytes";

  var c = 1024,
      d = b || 2,
      e = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"],
      f = Math.floor(Math.log(a) / Math.log(c));

  return parseFloat((a / Math.pow(c, f)).toFixed(d)) + " " + e[f]

}


function convertToRealSize(value) {

  var newVal = parseInt(value);

  if ( isNaN(newVal) || newVal <= 0 ) {
    return 'auto';
  }

  return newVal;

}


export {
  getProductImages,
  setCollectionImage,
  mergeNewDataIntoCurrent,
  convertAuthDataToString,
  addCollectionsToProduct,
  rejectedPromise,
  mapCollectsToProducts,
  mapCollectsToCollections,
  sanitizeErrorResponse,
  returnCustomError,
  getDefaultExitOptions,
  getCombinedExitOptions,
  returnOnlyFailedRequests,
  onlyFailedRequests,
  addSuccessNotice,
  addToWarningList,
  constructFinalNoticeList,
  onlyNonNotice,
  filterOutAnyNotice,
  emptyDataCount,
  filterOutSelectiveSync,
  filterOutSelectedDataForSync,
  filterOutEmptySets,
  formatBytes,
  isSyncingProducts,
  isSyncingCollections,
  hasConnection,
  setConnectionStatus,
  getErrorContents,
  filterForErrors,
  returnOnlyFirstError,
  getConnectionStatus,
  getRestErrorContents,
  convertToRealSize
}
