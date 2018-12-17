import {
  enableConnectionSubmit
} from '../utils/utils-dom';

import {
  clearSync
} from '../ws/wrappers.js';



/*

When the user closes any modal

All this does is clear the syncing cache, status
The clean up happens within the progressStatus

Returns: undefined

*/
function onModalClose() {

  // Cancel request when user clicks cancel button
  jQuery('.wps-btn-cancel, .wps-modal-close').unbind().on('click', async function(e) {

    clearSync();
    enableConnectionSubmit();
    WP_Shopify.manuallyCanceled = true;

  });

}

export {
  onModalClose
}
