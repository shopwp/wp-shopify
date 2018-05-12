import isError from 'lodash/isError';

import {
  createConnectorModal,
  injectConnectorModal,
  showConnectorModal,
  setConnectionStepMessage,
  showAdminNotice,
  updateModalButtonText,
  updateModalHeadingText,
  updateCurrentConnectionStepText,
  insertXMark,
  initCloseModalEvents,
  insertCheckmark,
  setConnectionNotice,
  addConnectorStepMessage,
  addNotice,
  showAnyWarnings,
  updateDomAfterSync
} from '../utils/utils-dom';

import {
  connectionInProgress,
  setConnectionProgress,
  setModalCache,
  syncIsCanceled,
  setWebhooksReconnect
} from '../ws/localstorage';

import {
  setSyncingIndicator,
  removeWebhooks,
  registerWebhooks
} from '../ws/ws';

import {
  syncWebhooks,
  syncOn
} from '../ws/syncing';

import {
  prepareBeforeSync
} from '../connect/connect';

import {
  syncOff,
  clearSync
} from '../ws/wrappers.js';

import {
  onModalClose
} from '../forms/events';

import {
  enable,
  disable,
  showSpinner,
  isWordPressError
} from '../utils/utils';

import {
  startProgressBar,
  mapProgressDataFromSessionValues,
  appendProgressBars,
  progressStatus
} from '../utils/utils-progress';

import {
  returnOnlyFailedRequests,
  constructFinalNoticeList,
  addToWarningList
} from '../utils/utils-data';


/*

Webhook re-sync

TODO: We could potentially enhance performance considerably if we do
checksum comparisons. Look into this.

*/
function onWebhooksSubmit() {

  jQuery(".wps-is-active #wps-button-webhooks")
    .unbind()
    .on('click', webhooksSubmitCallback);
}


/*

Webhook re-sync callback

*/
async function webhooksSubmitCallback(e) {

  e.preventDefault();

  return new Promise(async (resolve, reject) => {

    var warningList = [];

    prepareBeforeSync();
    updateModalHeadingText('Reconnecting Webhooks ...');
    setConnectionStepMessage('Preparing sync ...');
    setWebhooksReconnect(true);

    /*

    1. Turn sync on

    */
    try {
      var syncOnResponse = await syncOn();

    } catch (errors) {

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }

    insertCheckmark();
    setConnectionStepMessage('Removing any existing webhooks first ...');
    warningList = addToWarningList(warningList, syncOnResponse);


    /*

    2. Remove webhook

    */
    try {

      var removalErrors = await removeWebhooks(); // remove_webhooks

    } catch(errors) {

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }

    insertCheckmark();
    setConnectionStepMessage('Syncing new webhooks ...');
    warningList = addToWarningList(warningList, removalErrors);


    /*

    3. Start progress bar

    */
    try {

      var progressSession = await startProgressBar(true, ['webhooks']);

    } catch (errors) {

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }


    /*

    4. Begin polling for the status ... creates a cancelable loop

    */
    await progressStatus();
    appendProgressBars(progressSession.data);
    warningList = addToWarningList(warningList, progressSession);


    /*

    5. Syncing webhooks

    */
    try {

      var registerWebhooksResp = await syncWebhooks(removalErrors.data); // wps_ws_register_all_webhooks

    } catch(errors) {

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }


    warningList = addToWarningList(warningList, registerWebhooksResp);


    /*

    6. Turn sync off

    */
    try {
      await syncOff();

    } catch (errors) {

      updateDomAfterSync({
        noticeList: returnOnlyFailedRequests(errors)
      });

      resolve();
      return;

    }


    /*

    7. Finally update DOM

    */
    updateDomAfterSync({
      headingText: 'Finished syncing Webhooks',
      buttonText: 'Ok, let\'s go!',
      status: 'is-connected',
      stepText: 'Finished syncing webhooks',
      noticeList: constructFinalNoticeList(warningList),
      noticeType: 'success'
    });

  });


}


export {
  onWebhooksSubmit
};
