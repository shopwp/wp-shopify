import isError from 'lodash/isError';

import {
  createConnectorModal,
  injectConnectorModal,
  updateModalHeadingText,
  toggleActive,
  showAdminNotice
} from '../utils/utils-dom';

import {
  clearLocalstorageCache
} from '../ws/localstorage.js';

import {
  removeAllData
} from '../ws/ws.js';

import {
  onModalClose
} from '../forms/events';

import {
  enable,
  disable,
  showSpinner,
  hideSpinner,
  showLoader,
  hideLoader,
  isWordPressError
} from '../utils/utils';

import {
  clearAllCache
} from '../tools/cache';


/*

When clear submit form is submitted ...

*/
function onClearSubmit() {

  jQuery("#wps-button-clear-all-data").unbind().on('click', async function(e) {

    e.preventDefault();

    var $button = jQuery(this);
    var $spinner = $button.parent().find('.spinner');

    disable($button);
    toggleActive($spinner);
    showLoader($button);


    /*

    Step 1. Clearing current data

    */
    try {

      var removedResponse = await removeAllData();

      if (isWordPressError(removedResponse)) {
        throw removedResponse.data;

      } else if (isError(removedResponse)) {
        throw removedResponse;
      }

    } catch(errors) {

      showAdminNotice(errors, 'error');

    }


    /*

    Step 2. Clear all plugin cache

    */
    try {

      var clearAllCacheResponse = await clearAllCache();

      if (isWordPressError(clearAllCacheResponse)) {
        throw clearAllCacheResponse.data;

      } else if (isError(clearAllCacheResponse)) {
        throw clearAllCacheResponse;

      } else {
        showAdminNotice('Successfully removed all data', 'updated');

      }


    } catch(errors) {

      showAdminNotice(errors, 'error');

    }

    hideLoader($button);
    enable($button);


  });


}


export {
  onClearSubmit
};
