import {
  post
} from '../ws';

import {
  endpointOrders,
  endpointOrdersCount
} from './api-endpoints';


/*

Get Smart Collections

Returns: promise

*/
function getOrders(data = {}) {
  return post( endpointOrders(), data);
}


/*

Get Smart Collections Count

Returns: promise

*/
function getOrdersCount() {
  return post( endpointOrdersCount() );
}


export {
  getOrdersCount,
  getOrders
}
