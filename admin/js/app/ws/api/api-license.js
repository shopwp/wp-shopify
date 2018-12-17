import {
  get,
  post,
  deletion
} from '../ws';

import {
  endpointLicense
} from './api-endpoints';


/*

Get License Key Info

Returns: promise

*/
function getLicense(data = {}) {
  return get( endpointLicense(), data);
}


/*

Set License Key Info

Returns: promise

*/
function setLicense(data = {}) {
  return post( endpointLicense(), data);
}


/*

Set License Key Info

Returns: promise

*/
function deleteLicense(data = {}) {
  return deletion( endpointLicense(), data);
}


export {
  getLicense,
  setLicense,
  deleteLicense
}
