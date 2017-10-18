import {
  isError
} from 'lodash';

import {
  showAdminNotice,
  toggleActive,
} from '../utils/utils-dom';

import {
  clearLocalstorageCache
} from '../ws/localstorage.js';

import {
  setSyncingIndicator,
  removePluginData,
  clearCache
} from '../ws/ws.js';


import {
  enable,
  disable,
  showSpinner,
  hideSpinner,
  showLoader,
  hideLoader
} from '../utils/utils';


/*

Form Events Init

*/
function onCacheClear() {

  jQuery(".wps-is-active #wps-button-clear-cache").unbind().on('click', async function(e) {

    e.preventDefault();

    var $button = jQuery(this);
    var $spinner = $button.parent().find('.spinner');

    disable($button);
    toggleActive($spinner);
    showLoader($button);


    /*

    Step 1. Clear Localstorage cache

    */
    try {
      var clearLocalstorageResponse = await clearLocalstorageCache();

    } catch(clearLocalstorageCacheError) {

      hideLoader($button);
      showAdminNotice(clearLocalstorageCacheError, 'error');
      enable($button);
      return;

    }


    /*

    Step 2. Clear main cache

    */
    try {
      var clearCacheResponse = await clearCache();

      if (!clearCacheResponse.success) {
        throw new Error(clearCacheResponse.data);
      }

      showAdminNotice('Successfully cleared cache', 'updated');

    } catch(clearCacheError) {

      hideLoader($button);
      showAdminNotice(clearCacheError, 'error');
      enable($button);
      return;

    }

    hideLoader($button);
    enable($button);


  });


}

export {
  onCacheClear
}
