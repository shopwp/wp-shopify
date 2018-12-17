import {
  post
} from '../ws';

import {
  endpointVariants
} from './api-endpoints';


/*

Gets products

Returns: promise

*/
function getVariantIdFromOptions(data = {}) {
  return post( endpointVariants(), data);
}

export {
  getVariantIdFromOptions
}
