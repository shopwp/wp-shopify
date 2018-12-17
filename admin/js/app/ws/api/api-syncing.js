import {
  get,
  post
} from '../ws';

import {
  endpointSyncingStatus,
  endpointSyncingIndicator,
  endpointSyncingCounts
} from './api-endpoints';


/*

Get syncing status

Returns: promise

*/
function getSyncingStatus() {
  return get( endpointSyncingStatus() );
}

function setSyncingIndicator(data = {}) {
  return post( endpointSyncingIndicator(), data );
}

function saveCounts(data = {}) {
  return post( endpointSyncingCounts(), data );
}

export {
  getSyncingStatus,
  setSyncingIndicator,
  saveCounts
}
