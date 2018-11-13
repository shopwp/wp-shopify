import to from 'await-to-js';
import { cacheNoticeDismissal } from '../ws/ws';
import { showAdminNotice } from "../utils/utils-dom";
import { messageSettingsSuccessfulSave } from "../messages/messages";

import {
  isWordPressError,
  getJavascriptErrorMessage,
  getWordPressErrorMessage,
  getWordPressErrorType
} from "../utils/utils";

/*

Init

*/
function cacheAdminNoticeDismissal() {

  jQuery('.wps-notice .notice-dismiss').on('click', async function(event, el) {

    var $notice = jQuery(this).parent('.notice.is-dismissible');
    var dismiss_name = $notice.attr('data-dismiss-name');

    if (dismiss_name) {

      try {

        var [cacheError, cacheResponse] = await to( cacheNoticeDismissal(dismiss_name) );

      } catch (e) {
        console.error('WP Shopify Admin Notice Error: ', e);
      }

    }

  });

}


function initDismissNoticeEvents() {

  jQuery('.wps-notice.is-dismissible .notice-dismiss').on('click', e => {
    jQuery(e.currentTarget).parent().fadeOut();
  });

}


/*

Admin notice helper

*/
function showNotice(updateError, updateResponse) {

  if (updateError) {
    return showAdminNotice( getJavascriptErrorMessage(updateError), "error");
  }

  if (isWordPressError(updateResponse)) {
    return showAdminNotice(
      getWordPressErrorMessage(updateResponse),
      getWordPressErrorType(updateResponse)
    );
  }

  return showAdminNotice( messageSettingsSuccessfulSave(), "updated");

}


function hideNotice() {
  jQuery('#wps-errors .wps-notice').fadeOut();
}


/*

Init

*/
function noticesInit() {
  cacheAdminNoticeDismissal();
}

export {
  noticesInit,
  initDismissNoticeEvents,
  showNotice,
  hideNotice
}
