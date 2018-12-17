import {
  post
} from '../ws';

import {
  endpointProducts,
  endpointProductsCount,
  endpointPublishedProductIds
} from './api-endpoints';


/*

Gets products

Returns: promise

*/
function getProducts(data = {}) {
  return post( endpointProducts(), data);
}


/*

Gets products count

Returns: promise

*/
function getProductsCount() {
  return post( endpointProductsCount() );
}


/*

Gets published product ids

Returns: promise

*/
function getPublishedProductIds() {
  return post( endpointPublishedProductIds() );
}



export {
  getProductsCount,
  getProducts,
  getPublishedProductIds
}
