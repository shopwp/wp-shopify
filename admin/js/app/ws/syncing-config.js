import {
  getWordPressErrorMessage,
  getWordPressErrorType,
  getJavascriptErrorMessage
} from '../utils/utils';


function syncingConfigManualCancel() {

  return {
    xMark: true,
    stepText: 'Syncing canceled',
    headingText: 'Syncing canceled',
    noticeList: [{
      type: 'warning',
      message: 'The syncing process was manually canceled early.'
    }]
  }

}


function syncingConfigWebhooksSuccess() {

  return {
    noticeList: [{
      type: 'success',
      stepText: 'Finished syncing webhooks',
      message: 'Success! You\'ve finished reconnecting the Shopify webhooks.'
    }]
  }

}


function syncingConfigUnexpectedFailure() {

  return {
    xMark: true,
    stepText: 'Syncing failed unexpectedly',
    headingText: 'Syncing failed unexpectedly',
    noticeList: [{
      type: 'error',
      message: 'Syncing failed unexpectedly.'
    }]
  }

}


/*

Param: error  - Represents a WP_Error object sent from server

*/
function syncingConfigErrorBeforeSync(error) {

  return {
    xMark: true,
    stepText: 'Syncing failed',
    headingText: 'Syncing failed',
    noticeList: [{
      type: getWordPressErrorType(error),
      message: getWordPressErrorMessage(error)
    }]
  }

}


function syncingConfigJavascriptError(error) {

  return {
    xMark: true,
    stepText: 'Syncing failed from client-side',
    headingText: 'Syncing failed',
    noticeList: [{
      type: 'error',
      message: getJavascriptErrorMessage(error)
    }]
  }

}


function syncingConfigDisconnection(error) {

  return {
    headingText: 'Disconnected',
    stepText: 'Finished disconnecting'
  }

}


function syncingConfigSavedConnectionOnly(error) {

  return {
    headingText: 'Connection saved',
    stepText: 'Finished saving Shopify connection only'
  }

}


export {
  syncingConfigManualCancel,
  syncingConfigWebhooksSuccess,
  syncingConfigUnexpectedFailure,
  syncingConfigErrorBeforeSync,
  syncingConfigJavascriptError,
  syncingConfigDisconnection,
  syncingConfigSavedConnectionOnly
}
