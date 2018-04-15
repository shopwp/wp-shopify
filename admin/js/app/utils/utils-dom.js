import forOwn from 'lodash/forOwn';
import isArray from 'lodash/isArray';
import isEmpty from 'lodash/isEmpty';
import forEach from 'lodash/forEach';
import isPlainObject from 'lodash/isPlainObject';

import {
  connectInit
} from '../connect/connect';

import {
  getDefaultExitOptions,
  getCombinedExitOptions
} from './utils-data';

import {
  enable
} from './utils';

import {
  getModalCache,
  connectionInProgress,
  removeModalCache,
  removeConnectionProgress,
  removeConnectionNonce,
  clearLocalstorageCache,
  setConnectionProgress,
  isConnectionInProgress,
  syncIsCanceled
} from '../ws/localstorage';

/*

Create a checkmark
Returns: $element

*/
function createX() {
  return jQuery('<span class="dashicons dashicons-no"></span>');
};


/*

Create a checkmark
Returns: $element

*/
function createCheckmark() {
  return jQuery('<span class="dashicons dashicons-yes"></span>');
};


/*

Util: Inject the connector modal

*/
function injectConnectorModal($connectorModal) {
  jQuery('body').append($connectorModal);
};


/*

Util: Delete connector modal

*/
function ejectConnectorModal() {
  jQuery('body').find('.wps-connector-wrapper').remove();
};


/*

Util: Update status heading

*/
function updateConnectStatusHeading(status) {

  if(status === 'is-connected') {
    jQuery('.wps-status-heading .wps-status').removeClass('is-disconnected').addClass('is-connected').text('Connected');

  } else {
    jQuery('.wps-status-heading .wps-status').removeClass('is-connected').addClass('is-disconnected').text('Disconnected');

  }

}


/*

Show the connector modal

*/
function showConnectorModal($connectorModal) {

  if ($connectorModal.length) {

    if (typeof $connectorModal === 'string') {
      jQuery($connectorModal).find('.wps-connector-progress').show();

    } else {
      $connectorModal.find('.wps-connector-progress').show();
    }

  } else {
    return false;
  }

};


/*

Creates a new connector modal if one doesn't already exist
Returns: $element
TODO: Needs dynamic image paths

*/
function createConnectorModal(heading = 'Connecting ...', cancelText = 'Cancel connection') {

  if (getModalCache() === null) {

    return jQuery('<div class="wps-connector-wrapper"><div class="wps-connector wps-connector-progress wps-animated wps-fadeInDown"><h1 class="wps-connector-heading"><span>' + heading + '</span> <img class="wps-connector-logo" src="' + WP_Shopify.pluginsDirURL + 'admin/imgs/shopify.svg" /> to <img class="wps-connector-logo" src="' + WP_Shopify.pluginsDirURL + 'admin/imgs/logo-wp.svg" /></h1><div class="l-row"><button type="button" name="button" class="button button-primary wps-btn wps-btn-cancel">' + cancelText + '</button></div><div class="wps-connector-content"></div></ div></div>');

  } else {
    return getModalCache();
  }

};


/*

Change text of connector modal

*/
function updateModalHeadingText(text) {
  jQuery('.wps-connector-heading span').text(text);
}


/*

Adds a message to the step
Returns: undefined

*/
function addConnectorStepMessage(content, type = '', supportingMessage = '') {

  if ( !syncIsCanceled() ) {

    var $notice;

    if (type === 'error') {
      $notice = jQuery('<div class="wps-progress-notice wps-progress-notice-error"><div class="wps-progress-notice-group"><span class="wps-progress-text">' + content + '<small class="wps-progress-text-supporting">' + supportingMessage + '</small></span></div></div>');

    } else {
      $notice = jQuery('<div class="wps-progress-notice wps-progress-notice-success"><div class="wps-progress-notice-group"><span class="wps-progress-text">' + content + '<small class="wps-progress-text-supporting">' + supportingMessage + '</small></span><div class="spinner is-active"></div></div></div>');

    }

    jQuery('.wps-connector-wrapper').find('.wps-connector-content').prepend($notice);

  }

};


/*

Insert Checkmark

*/
function insertCheckmark() {
  jQuery('.wps-connector-content .wps-progress-notice:first-child').append( createCheckmark() );
  jQuery('.wps-connector-content .wps-progress-notice:first-child .spinner').removeClass('is-active');
  jQuery('.wps-connector-content .wps-progress-notice:first-child').addClass('is-inactive');
}


/*

Insert X Mark

*/
function insertXMark() {
  jQuery('.wps-connector-content .wps-progress-notice:first-child').append( createX() );
  jQuery('.wps-connector-content .wps-progress-notice:first-child .spinner').removeClass('is-active');
}


/*

Inserts a step in a connection process

*/
function setConnectionStepMessage(message, supportingMessage = '') {

  if (connectionInProgress() !== 'false') {
    addConnectorStepMessage(message, '', supportingMessage);
  }

}


/*

Inserts a step in a connection process

*/
function setConnectionNotice(message, type) {
  jQuery('.wps-connector-heading').after('<div class="notice notice-' + type + '">' + message + '</div>');
}


/*

Inserts a step in a connection process

*/
function addNotice(message, type) {

  var $heading = jQuery('.wps-connector-heading');
  var $existingNotices = $heading.next('.notice');

  if ($existingNotices.length) {
    $existingNotices.after('<div class="notice notice-' + type + '">' + message + '</div>');

  } else {
    setConnectionNotice(message, type);
  }


}


/*

Close the connector modal
Returns: undefined

*/
function initCloseModalEvents() {

  jQuery('.wps-btn-cancel').unbind().on('click', function() {

    jQuery('.wps-connector-wrapper').remove();
    jQuery(document).unbind();
    clearLocalstorageCache();

  });

  // Cancel request when user clicks outside modal ...
  jQuery(document).on('click', function(event) {

    if (!jQuery(event.target).closest('.wps-connector').length) {
      jQuery('.wps-connector-wrapper').remove();
      jQuery(document).unbind();
      clearLocalstorageCache();
    }

  });

  // Cancel request when user hits escape ...
  jQuery(document).keyup(function(e) {

    jQuery('.wps-connector-wrapper').remove();
    jQuery(document).unbind();
    clearLocalstorageCache();

  });

};


/*

Toggle an element active / inactive (hides / shows)

*/
function toggleActive($element) {
  $element.toggleClass('wps-is-active');
};


/*

Util: Stop spinners
Returns: $element without .is-active class
TODO: make dom element instead of classs

*/
function stopSpinner(spinner) {
  jQuery(spinner).removeClass('wps-is-active');
};


/*

Show canceling connection

*/
function updateCurrentConnectionStepText(text) {
  jQuery('.wps-connector-content .wps-progress-notice:first-child .wps-progress-text').text(text);
};


/*

Update Modal Text

*/
function updateModalButtonText(text) {
  jQuery('.wps-btn-cancel').text(text);
}



/*

Update Modal Text

*/
function showAdminNotice(message, type) {

  var $msgContainer = jQuery('#wps-errors');

  $msgContainer.removeClass('wps-is-hidden').html('<div class="notice ' + type + '"><p><strong>' + message + '</strong></p></div>');

  var $msg = $msgContainer.find('.notice');

  if (type === 'updated') {

    setTimeout(function() {

      $msg.slideUp('fast', function() {
        $msgContainer.addClass('wps-is-hidden');
      });

    }, 5000);

  }

}


/*

Remove Checkmarks

*/
function removeCheckmarks($form) {
  $form.find('input').removeClass('valid');
}


/*

Clear Connect Inputs

*/
function clearConnectInputs() {
  jQuery('#wps-connect .wps-form-group input').val('').removeClass('valid').prop('disabled', false);
}


/*

Clear Connect Inputs

*/
function disableConnectInputs() {
  jQuery('#wps-connect .wps-form-group input').prop('disabled', true).attr('disabled', true);
}


/*

Reset Connect Submit

*/
function resetConnectSubmit() {

  jQuery('#submitDisconnect, #wps-connect .wps-button-group input[type="submit"]')
    .val('Connect your Shopify store')
    .prop('disabled', false)
    .attr('disabled', false)
    .attr('name', 'submitConnect')
    .attr('id', 'submitConnect');

}


/*

Set Disconnect Submit

*/
function setDisconnectSubmit() {

  jQuery('#submitConnect')
    .val('Disconnect your Shopify store')
    .prop('disabled', false)
    .attr('disabled', false)
    .attr('name', 'submitDisconnect')
    .attr('id', 'submitDisconnect');

  disableConnectInputs();

}


/*

Set Disconnect Submit

*/
function addStopConnectorClass() {
  jQuery('.wps-connector').addClass('wps-is-stopping');
}


/*

Show any warnings

*/
function showAnyWarnings(warnings, msg = '') {

  var type = 'warning';

  forOwn(warnings, (value, key) => {
    addNotice(msg + key, type);
  });

}



/*

Resets the DOM elements related to the connector

*/
function resetConnectionDOM() {

  // clearConnectInputs();
  initCloseModalEvents();
  resetConnectSubmit();

  jQuery('.wps-connector').addClass('wps-is-finished');
  enable(jQuery('.wps-btn-cancel'));

  setConnectionProgress("false");

}


/*

Appends notice
TODO: Move the plain object check elsewhere

*/
function appendNotice(notice) {

  if (!isPlainObject(notice)) {
    notice = {
      type: 'warning',
      message: notice
    }
  }

  jQuery('.wps-connector-heading').after('<div class="notice notice-' + notice.type + '">' + notice.message + '</div>');

}


/*

Showing error message

*/
function updateNotices(exitOptions) {

  if (isArray(exitOptions.noticeList) && !isEmpty(exitOptions.noticeList)) {
    forEach(exitOptions.noticeList, appendNotice);
  }

}

function replaceSpinnersWithCheckmarks() {

  jQuery('.wps-progress-notice').not('.is-inactive').each(function() {

    var $notice = jQuery(this);
    var $spinner = $notice.find('.spinner');

    if ($spinner.length) {
      $spinner.remove();
      $notice.append(createCheckmark());
    }

    $notice.addClass('is-inactive');

  });

}


/*

Runs as the final step after:
  - Disconnecting
  - Connecting
  - Any erors
  - Manual disconnecting

*/
function updateDomAfterSync(customOptions = {}) {

  const exitOptions = getCombinedExitOptions(customOptions);

  resetConnectionDOM();

  updateModalHeadingText(exitOptions.headingText);
  updateModalButtonText(exitOptions.buttonText);
  updateCurrentConnectionStepText(exitOptions.stepText);
  updateConnectStatusHeading(exitOptions.status);
  updateNotices(exitOptions);

  if (exitOptions.status === 'is-connected') {
    setDisconnectSubmit();

  } else {
    resetConnectSubmit();
  }

  enable( getConnectorCancelButton() );
  enable( getToolsButtons() );

  replaceSpinnersWithCheckmarks();

  // Safe to reconnect again -- reattaches the submit form handler
  connectInit();


}


/*

Returns the connector cancel button

*/
function getConnectorCancelButton() {
  return jQuery('.wps-connector .wps-btn-cancel');
}


/*

Returns all the Tools buttons

*/
function getToolsButtons() {
  return jQuery('#wps-button-sync, #wps-button-webhooks');
}


export {
  createX,
  createCheckmark,
  injectConnectorModal,
  showConnectorModal,
  createConnectorModal,
  addConnectorStepMessage,
  initCloseModalEvents,
  stopSpinner,
  insertCheckmark,
  insertXMark,
  removeCheckmarks,
  updateModalButtonText,
  updateModalHeadingText,
  updateCurrentConnectionStepText,
  setConnectionStepMessage,
  setConnectionNotice,
  showAdminNotice,
  toggleActive,
  ejectConnectorModal,
  updateConnectStatusHeading,
  clearConnectInputs,
  resetConnectSubmit,
  setDisconnectSubmit,
  addNotice,
  showAnyWarnings,
  updateDomAfterSync,
  resetConnectionDOM,
  addStopConnectorClass,
  getConnectorCancelButton,
  getToolsButtons
};
