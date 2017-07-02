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

Create Products Model

Currently hitting the Shopify API for every product to get the collects
assosicated with them. TODO: Maybe we can get the collects another way
without needing to make this expensive operation.

*/
function getCollectsForProduct(products) {

  var limiter = new Bottleneck(2, 500);

  //
  // Returns a promise
  //
  async function throttleMe(product) {
    return await getCollectsFromProductID(product.productId);
  }

  function hello(product) {
    return limiter.schedule(throttleMe, product);
  }

  //
  // Taking each product within 'products' and mapping
  // it to a promise
  //
  return Promise.all(
    R.map(hello, products)
  );

};


/*

Create Products Model

*/
function createCollectionsModel(collections) {
  return R.map(mapCollectionsModel, collections);
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
  return R.unionWith(R.eqProps('domain'), newAuthData, currentAuthData);
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

    // console.log('collec to product prd: ', product);

    var finalCollectionsArray = [];

    collects.forEach(function(collect) {

      // console.log('collec to product collect: ', collect);

      // If product ID matches collect ID
      if (product.productId === collect.product_id) {
        finalCollectionsArray = R.concat(product.productCollection, collect.productCollection);
      }

    });

    product.productCollection = finalCollectionsArray;

    return product;

  });

}


/*

Creates the intermediary collect model used to combine
products and collections into a single data structure.

*/
function createCollectModel(collects) {

  var collectModel = [];

  collects.forEach(function(product, index) {

    var id;

    if(product.collects !== undefined && product.collects.length) {
      id = product.collects[0].product_id;

    } else {
      id = null;

    }

    if (id) {

      collectModel.push({
        'product_id': id,
        'productCollection': R.map(function(collect) {

          return collect.collection_id;

        }, product.collects)
      });

    }

  });

  return collectModel;

}


/*

Adds matching products to collection object

*/
function addProductsToCollection(collectionsAssignedProducts, collections) {

  var newCollections = collections;

  R.forEach(function(collectionWithProducts) {
    console.log("collectionWithProducts: ", collectionWithProducts);

    // Loop through each collectionWithProducts ...
    R.forEach(function collectionWithProductsHandlder(product) {

      // Loop through each product ...
      R.forEach(function addCollectionToProductHandlder(collection) {

        // If product has same ID as the collection ..
        if(collect.product_id === product.productId) {

          // Add collection to product ..
          product.productCollection.push(collect);

        }

      }, newCollections);

    }, product);

  }, collectionsAssignedProducts);

  return newCollections;

}





export {
  getProductImages,
  mapProductsModel,
  mapCollectionsModel,
  getCollectsForProduct,
  setCollectionImage,
  createNewAuthData,
  convertAuthDataToJSON,
  mergeNewDataIntoCurrent,
  convertAuthDataToString,
  createCollectionsModel,
  addCollectionsToProduct,
  addProductsToCollection,
  createCollectModel,
  createProductsModel,
  controlPromise,
  rejectedPromise,
  mapCollectsToProducts,
  mapCollectsToCollections
};
