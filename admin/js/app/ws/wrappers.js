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
  isSyncingProducts,
  isSyncingCollections
} from '../utils/utils-data';

import {
  setCancelSync,
  syncIsCanceled,
  clearLocalstorageCache
} from './localstorage';

import {
  setSyncingIndicator,
  setProductPostsRelationships,
  setCollectionPostsRelationships,
  getAllCollections,
  getSelectedCollections,
  resetNoticeFlags,
  clearCache,
  removeConnectionData,
  deletePostsAndSyncedData,
  deleteOnlySyncedData
} from '../ws/ws';


/*

End Sync
expire_sync

*/
async function syncOff() {
  return setSyncingIndicator(0);
}


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

    if (isSyncingProducts()) {

      var [error, data] = await to( setProductPostsRelationships() );

      error ? reject(error) : resolve(data);

    } else {
      resolve();
    }


  });

}


/*

Fires off the process of attaching post ids to collections

*/
function checkForCollectionPostsRelationships() {

  return new Promise(async function(resolve, reject) {

    if (isSyncingCollections()) {

      var [error, data] = await to( setCollectionPostsRelationships() );

      error ? reject(error) : resolve(data);

    } else {
      resolve();
    }

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


function resetNoticesAndClearCache() {

  return Promise.all([
    resetNoticeFlags(),
    clearCache()
  ]);

}


function checkPostRelationships() {

  return Promise.all([
    checkForProductPostsRelationships(),
    checkForCollectionPostsRelationships()
  ]);

}


function deleteStandAloneData() {

  return Promise.all([
    deletePostsAndSyncedData(),
    removeConnectionData(),
    resetNoticeFlags()
  ]);

}


function noConnectionReset() {

  return Promise.all([
    clearCache(),
    deleteOnlySyncedData(),
    removeConnectionData(),
    resetNoticeFlags()
  ]);

}


export {
  syncOff,
  clearSync,
  checkForProductPostsRelationships,
  checkForCollectionPostsRelationships,
  getSelectiveCollections,
  resetNoticesAndClearCache,
  checkPostRelationships,
  deleteStandAloneData,
  noConnectionReset
}
