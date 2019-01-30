import {
  post
} from '../ws';

import {
  endpointToolsClearCache
} from './api-endpoints';


/*

Gets published product ids

Returns: promise

*/
function clearCache() {
  return post( endpointToolsClearCache() );
}


export {
  clearCache
}
