import {
  showAdminNotice,
  toggleActive,
} from '../utils/utils-dom';

import {
  clearLocalstorageCache
} from '../ws/localstorage.js';

import {
  setSyncingIndicator,
  clearCache
} from '../ws/ws.js';

import {
  enable,
  disable,
  showSpinner,
  hideSpinner,
  showLoader,
  hideLoader,
  isWordPressError
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

    try {

      var clearAllCacheResponse = await clearAllCache();

      if (isWordPressError(clearAllCacheResponse)) {
        throw new Error(clearAllCacheResponse.data);

      } else {
        showAdminNotice('Successfully cleared cache', 'updated');
      }

    } catch(errors) {
      showAdminNotice(errors, 'error');
    }

    hideLoader($button);
    enable($button);

  });

}


/*

Clear All Cache

*/
function clearAllCache() {

  return new Promise(async function(resolve, reject) {

    /*

    Step 2. Clear main cache

    */
    try {
      var clearCacheResponse = await clearCache(); // wps_clear_cache

      if (isWordPressError(clearCacheResponse)) {
        reject(clearCacheResponse.data);
      }

    } catch(clearCacheError) {
      reject(clearCacheError);

    }

    resolve(clearCacheResponse);

  });

}

export {
  onCacheClear,
  clearAllCache
}
