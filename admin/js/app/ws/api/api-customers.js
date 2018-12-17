import {
  post
} from '../ws';

import {
  endpointCustomers,
  endpointCustomersCount
} from './api-endpoints';


/*

Get Smart Collections

Returns: promise

*/
function getCustomers(data = {}) {
  return post( endpointCustomers(), data);
}


/*

Get Smart Collections Count

Returns: promise

*/
function getCustomersCount() {
  return post( endpointCustomersCount() );
}


export {
  getCustomersCount,
  getCustomers
}
