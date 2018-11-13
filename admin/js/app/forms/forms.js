import {
  enable,
  disable,
  hasVals,
  showLoader
} from '../utils/utils';

import {
  showAdminNotice
} from '../utils/utils-dom';

import {
  getProducts
} from '../ws/ws';

import { connectInit } from '../connect/connect';
import { disconnectInit } from '../disconnect/disconnect';


/*

On Input Blur

*/
function onInputBlur() {

  var $buttonSubmit = jQuery('.wps-admin-section #submitConnect');
  var $forms = jQuery('.wps-admin-form');
  var formInputClass = 'input';

  $forms.on('blur', formInputClass, function formInputBlurHandler() {

    var $inputs = $forms.find('input[required]');

    if(hasVals($inputs)) {
      enable($forms.find('input[type="submit"]'));

    } else {
      disable($forms.find('input[type="submit"]'));

    }

  });

}


/*

Remove event handlers on connection form ...

*/
function unbindConnectForm() {

  jQuery('#wps-connect').off("submit.connect");
  jQuery("#wps-connect").off('submit.validate');
  jQuery('#wps-connect').data('validator', null);

}


/*

Remove event handlers on disconnection form ...

*/
function unbindDisconnectForm() {

  jQuery('#wps-connect').off("submit.disconnect");

}


/*

Format Connector Form Data

*/
function formatConnectorFormData(formData) {

  var formData = JSON.parse(JSON.stringify(formData));

  return formData.reduce(function(obj, item) {
    obj[item.name] = item.value;
    return obj;
  }, {} );

}


/*

Form Connection Rules

*/
function formConnectionRules() {

  return {
    "js_access_token": {
      alphaNumeric: true
    },
    "shared_secret": {
      alphaNumeric: true
    },
    "api_key": {
      alphaNumeric: true
    },
    "password": {
      alphaNumeric: true
    },
    "domain": {
      domainRule: true
    }
  }

}


function initConnectFormSubmit() {

  var $formConnect = jQuery("#wps-connect");
  var $submitButton = $formConnect.find('input[type="submit"]');

  if ($submitButton.attr('name') === 'submitDisconnect') {
    disconnectInit();

  } else {
    connectInit();
  }

}


/*

Form Events Init

*/
function formEventsInit() {
  initConnectFormSubmit();
}

export {
  formEventsInit,
  unbindConnectForm,
  unbindDisconnectForm,
  formatConnectorFormData,
  formConnectionRules
};
