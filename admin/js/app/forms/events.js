import to from 'await-to-js';

import {
  updateModalHeadingText,
  updateCurrentConnectionStepText,
  updateDomAfterSync,
  enableConnectionSubmit
} from '../utils/utils-dom';

import {
  setCancelSync,
  clearLocalstorageCache
} from '../ws/localstorage';

import {
  resetProgressIndicators
} from '../utils/utils';

import {
  cleanUpAfterSync
} from '../utils/utils-progress';

import {
  constructFinalNoticeList
} from '../utils/utils-data';

import {
  setSyncingIndicator,
  killSyncing
} from '../ws/ws';

import {
  clearSync
} from '../ws/wrappers.js';



/*

When the user closes any modal

All this does is clear the syncing cache, status, and runs wp_die().
The clean up happens within the progressStatus

Returns: undefined

*/
function onModalClose() {

  // Cancel request when user clicks cancel button
  jQuery('.wps-btn-cancel').unbind().on('click', async function(e) {

    clearSync();
    enableConnectionSubmit();
    WP_Shopify.manuallyCanceled = true;

    if (WP_Shopify.isSyncing) {

      var [killSyncingError, killSyncingData] = await to( killSyncing() );

    }

  });

};

export {
  onModalClose
}
