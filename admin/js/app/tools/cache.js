import to from 'await-to-js';

import {
  showAdminNotice,
  toggleActive,
} from '../utils/utils-dom';

import {
  clearLocalstorageCache
} from '../ws/localstorage';

import {
  clearAllCache
} from '../ws/wrappers';

import {
  enable,
  disable,
  showLoader,
  hideLoader,
  isWordPressError,
  getWordPressErrorMessage,
  getWordPressErrorType,
  getJavascriptErrorMessage
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

    clearLocalstorageCache();

    var [clearError, clearResp] = await to( clearAllCache() );

    if (clearError) {
      showAdminNotice( getJavascriptErrorMessage(clearError) );
    }

    if (isWordPressError(clearResp)) {
      getWordPressErrorMessage(clearResp),
      getWordPressErrorType(clearResp)
    }

    showAdminNotice('Successfully cleared the WP Shopify cache', 'updated');

  });

}


export {
  onCacheClear
}
