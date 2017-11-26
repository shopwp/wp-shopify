import unionWith from 'ramda/es/unionWith';
import eqProps from 'ramda/es/eqProps';
import concat from 'ramda/es/concat';
import map from 'ramda/es/map';

import {
  getNonce
} from './utils';

import {
  getCollectsFromProductID
} from '../ws/ws';

import {
  connectionInProgress
} from '../ws/localstorage';


/*

Control promise

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

  if(products !== undefined) {
    return products.map(mapProductsModel);
  }

}


/*

Map Collections Model

*/
function mapCollectionsModel(collection) {

  if(collection !== undefined) {
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

  if(collection.hasOwnProperty('image')) {
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
  return unionWith(eqProps('domain'), newAuthData, currentAuthData);
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


function sanitizeErrorResponse(error) {

  if (error.hasOwnProperty('data')) {
    return error.data;

  } else {
    return error;

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
  sanitizeErrorResponse
};
