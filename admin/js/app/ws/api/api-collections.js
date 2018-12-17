import {
  post
} from '../ws';

import {
  endpointSmartCollections,
  endpointSmartCollectionsCount,
  endpointCustomCollections,
  endpointCustomCollectionsCount,
  endpointAllCollections
} from './api-endpoints';


/*

Get Smart Collections

Returns: promise

*/
function getSmartCollections(data = {}) {
  return post( endpointSmartCollections(), data);
}


/*

Get Custom Collections

Returns: promise

*/
function getCustomCollections(data = {}) {
  return post( endpointCustomCollections(), data);
}


/*

Get Smart Collections Count

Returns: promise

*/
function getSmartCollectionsCount() {
  return post( endpointSmartCollectionsCount() );
}


/*

Get Smart Collections Count

Returns: promise

*/
function getCustomCollectionsCount() {
  return post( endpointCustomCollectionsCount() );
}


/*

Get Smart Collections Count

Returns: promise

*/
function getAllCollections() {
  return post( endpointAllCollections() );
}


export {
  getSmartCollectionsCount,
  getSmartCollections,
  getCustomCollectionsCount,
  getCustomCollections,
  getAllCollections
}
