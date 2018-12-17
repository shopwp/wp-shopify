import to from 'await-to-js';
import { post } from '../ws/ws';
import { endpointNoticesDismiss } from '../ws/api/api-endpoints';

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

      var [cacheError, cacheResponse] = await to( post(
        endpointNoticesDismiss(),
        { dismiss_name: dismiss_name }
      ));

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
