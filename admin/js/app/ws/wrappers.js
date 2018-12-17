import to from 'await-to-js';

import {
  updateModalHeadingText,
  setConnectionStepMessage,
  insertCheckmark,
  addStopConnectorClass,
  getConnectorCancelButton
} from '../utils/utils-dom';

import {
  disable
} from '../utils/utils';

import {
  setCancelSync,
  syncIsCanceled,
  clearLocalstorageCache
} from './localstorage';

import {
  deletion
} from '../ws/ws';

import {
  setProductPostsRelationships,
  setCollectionPostsRelationships
} from './api/api-posts';

import {
  getSelectedCollections
} from './api/api-settings';

import {
  endpointConnection,
  endpointToolsClearAll,
  endpointSyncingNotices,
  endpointToolsClearSynced,
  endpointNotices,
  endpointToolsClearCache
} from './api/api-endpoints';

import {
  getAllCollections
} from '../ws/api/api-collections';

import {
  isSyncingProducts,
  isSyncingCollections
} from '../globals/globals-syncing';


/*

Clear Sync
Runs on errors or manual disconnects

*/
function clearSync() {

  if (!syncIsCanceled()) {

    addStopConnectorClass();
    disable( getConnectorCancelButton() );
    updateModalHeadingText('Stopping');

    insertCheckmark();
    setConnectionStepMessage('Canceling, please wait ...', '(This may take up to 30 seconds)');
    setCancelSync(true);

  }

}


/*

Fires off the process of attaching post ids to products

*/
function checkForProductPostsRelationships() {

  return new Promise(async function(resolve, reject) {

    if ( !isSyncingProducts() ) {
      return resolve();
    }

    var [error, data] = await to( setProductPostsRelationships() );

    error ? reject(error) : resolve(data);

  });

}


/*

Fires off the process of attaching post ids to collections

*/
function checkForCollectionPostsRelationships() {

  return new Promise(async function(resolve, reject) {

    if ( !isSyncingCollections() ) {
      return resolve();
    }

    var [error, data] = await to( setCollectionPostsRelationships() );

    error ? reject(error) : resolve(data);

  });

}


/*

Gets both the selected collections and all available collections

*/
function getSelectiveCollections() {

  return Promise.all([
    getAllCollections(),
    getSelectedCollections()
  ]);

}


function clearAllCache() {

  return Promise.all([
    deletion( endpointNotices() ),
    deletion( endpointSyncingNotices() ),
    deletion( endpointToolsClearCache() )
  ]);

}


function setPostRelationships() {

  return Promise.all([
    checkForProductPostsRelationships(),
    checkForCollectionPostsRelationships()
  ]);

}


function deleteStandAloneData() {

  return Promise.all([
    deletion( endpointToolsClearAll() ),
    deletion( endpointConnection() )
  ]);

}


function noConnectionReset() {

  return Promise.all([
    deletion( endpointToolsClearCache() ),
    deletion( endpointToolsClearSynced() ),
    deletion( endpointConnection() )
  ]);

}


export {
  clearSync,
  checkForProductPostsRelationships,
  checkForCollectionPostsRelationships,
  getSelectiveCollections,
  clearAllCache,
  setPostRelationships,
  deleteStandAloneData,
  noConnectionReset
}
