import {
  clearAllCache
} from '../tools/cache';

import {
  updateDomAfterSync,
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
  setConnectionProgress,
  connectionInProgress,
  isConnectionInProgress,
  setCancelSync,
  syncIsCanceled
} from './localstorage';

import {
  setSyncingIndicator,
  endProgress,
  removeConnectionData
} from '../ws/ws';

import {
  showCleanDataMessaging
} from '../disconnect/disconnect';


/*

End Sync

*/
async function syncOff() {

  return Promise.all([

    // Empty and end the $_SESSION
    await endProgress(), // wps_progress_bar_end

    // Clears the LS cache and any Transients
    await clearAllCache() // wps_clear_cache

  ]);

}


/*

Clear Sync
Runs on errors or manual disconnects

*/
function clearSync() {

  return new Promise(async function clearSyncHandler(resolve, reject) {

    if (!syncIsCanceled()) {

      addStopConnectorClass();
      disable( getConnectorCancelButton() );

      insertCheckmark();

      updateModalHeadingText('Stopping ...');
      setConnectionStepMessage('Please wait ...', '(This may take up to 30 seconds)');

      setCancelSync(true);

      await syncOff();

      resolve();
      return;

    } else {

    }

  });

}


export {
  syncOff,
  clearSync
}
