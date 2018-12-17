import {
  post
} from '../ws';

import {
  endpointCollects,
  endpointCollectsCount
} from './api-endpoints';


/*

Get Smart Collections

Returns: promise

*/
function getCollects(data = {}) {
  return post( endpointCollects(), data);
}


/*

Get Smart Collections Count

Returns: promise

*/
function getCollectsCount() {
  return post( endpointCollectsCount() );
}


export {
  getCollectsCount,
  getCollects
}
