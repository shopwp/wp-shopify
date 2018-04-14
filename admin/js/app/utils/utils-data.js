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

import {
  getNonce
} from './utils';

import {
  getCollectsFromProductID
} from '../ws/ws';

import {
  connectionInProgress,
  getStartingURL
} from '../ws/localstorage';


/*

Always returns a Promise

*/
function controlPromise(options) {

  if ( connectionInProgress() === 'false' ) {

    return new Promise(function (resolve, reject) {
      reject('Connection stopped by user.');
    });

  } else {

    return jQuery.ajax(options);

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

Map Products Model

*/
function mapProductsModel(product) {

  if (product !== undefined) {

    return {
      productTitle: product.title,
      productDescription: product.body_html,
      productId: product.id,
      productHandle: product.handle,
      productImages: getProductImages(product),
      productTags: product.tags,
      productVendor: product.vendor,
      productVariants: product.variants,
      productType: product.product_type,
      productOptions: product.options,
      productCollection: []
    };

  }

};


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

Create the actual products model

*/
function createProductsModel(products) {

  if (products !== undefined) {
    return products.map(mapProductsModel);
  }

}


/*

Map Collections Model

*/
function mapCollectionsModel(collection) {

  if (collection !== undefined) {

    return {
      collectionTitle: collection.title,
      collectionDescription: collection.body_html,
      collectionId: collection.id,
      collectionHandle: collection.handle,
      collectionImage: setCollectionImage(collection),
      collectionProducts: []
    };

  }

};


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

Creates data template
Returns: Object

*/
function createNewAuthData() {

  return [{
    "domain": window.location.hostname,
    "url": window.location.href,
    "nonce": getNonce(),
    "timestamp": Date.now(),
    "shop": jQuery('#wps_settings_connection_domain').val()
  }];
};


/*

Converts string to JSON
Returns: JS value (authUserData === Object)

*/
function convertAuthDataToJSON(authUserData) {

  if (authUserData === null) {
    return createNewAuthData();

  } else {
    return jQuery.parseJSON(authUserData);
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
    buttonText: 'Exit Shopify Sync',
    xMark: false,
    noticeList: [{
      type: 'warning',
      message: 'Syncing manually canceled early'
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

Return Only Failed Requests

*/
function returnOnlyFailedRequests(noticeList) {

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
    return map(filter(noticeList, onlyFailedRequests), sanitizeErrorResponse);

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

Only non notice

*/
function onlyAvailableSyncOptions(obj) {

  if (WP_Shopify.selective_sync.all) {
    return obj;

  } else {

    var onlySelectedSyncs = filterOutDeselectedSyncs(WP_Shopify.selective_sync);
    var nameOfSync = Object.getOwnPropertyNames(obj)[0];


    if (onlySelectedSyncs.hasOwnProperty(nameOfSync)) {

      return obj;

    } else {

      if (nameOfSync === 'products') {

        if ( has(onlySelectedSyncs, 'tags') ) {
          return obj;
        }

        if ( has(onlySelectedSyncs, 'images') ) {
          return obj;
        }

      }

    }

  }

}


/*

Filter Out Any Deselected Selective Syncs

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

  var filteredSyncs = filter(array, onlyAvailableSyncOptions);

  return filteredSyncs;

}


/*

Filter Out Any Notice

*/
function filterOutSelectedDataForSync(array, arrayOfFilters) {

  return filter(array, function(dataSet) {

    return find(arrayOfFilters, function(filter) {

      return !dataSet[filter];

    });

  });

  return array;

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


/*

Add Success Notice

*/
function addSuccessNotice() {

  return [{
    type: 'success',
    message: 'Success! You\'re now connected and syncing with Shopify.'
  }];

}


/*

Construct Final Notice List

*/
function constructFinalNoticeList(anyWarnings) {
  return union(addSuccessNotice(), anyWarnings);
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


export {
  getProductImages,
  mapProductsModel,
  mapCollectionsModel,
  setCollectionImage,
  createNewAuthData,
  convertAuthDataToJSON,
  mergeNewDataIntoCurrent,
  convertAuthDataToString,
  addCollectionsToProduct,
  createProductsModel,
  controlPromise,
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
  filterOutEmptySets
};
