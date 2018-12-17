import {
  post,
  deletion
} from '../ws';

import {
  endpointToolsClearCache
} from './api-endpoints';


/*

Gets published product ids

Returns: promise

*/
function clearCache() {
  return deletion( endpointToolsClearCache() );
}


export {
  clearCache
}
