import {
  cacheNoticeDismissal
} from '../ws/ws';

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

  jQuery('.wps-notice').on('click', '.notice-dismiss', async function(event, el) {

    var $notice = jQuery(this).parent('.notice.is-dismissible');
    var dismiss_name = $notice.attr('data-dismiss-name');

    if (dismiss_name) {

      try {
        var response = await cacheNoticeDismissal(dismiss_name);

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


/*

Init

*/
function noticesInit() {
  cacheAdminNoticeDismissal();
}

export {
  noticesInit,
  initDismissNoticeEvents,
  showNotice
}
