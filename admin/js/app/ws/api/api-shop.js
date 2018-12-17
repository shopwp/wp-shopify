import {
  post
} from '../ws';

import {
  endpointShop,
  endpointShopCount
} from './api-endpoints';


/*

Get Smart Collections

Returns: promise

*/
function getShop(data = {}) {
  return post( endpointShop(), data);
}


/*

Get Smart Collections Count

Returns: promise

*/
function getShopCount() {
  return post( endpointShopCount() );
}


export {
  getShopCount,
  getShop
}
