import {
  enable,
  disable,
  hasVals,
  showLoader,
  hideLoader
} from '../utils/utils';

import {
  showAdminNotice
} from '../utils/utils-dom';

import {
  addWebhook,
  getProductVariants,
  getProducts,
  uninstallPlugin
} from '../ws/ws.js';


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

On plugin uninstall ...

*/
function onUninstall() {

  jQuery('#wps-btn-uninstall').on('click', async function uninstallHandler(event) {

    showLoader(jQuery(this));

    try {
      var response = await uninstallPlugin();

    } catch(error) {

      hideLoader(jQuery(this));
      showAdminNotice("Error removing store data: " + error, 'error');
      return;

    }

    hideLoader(jQuery(this));
    showAdminNotice("Successfully removed store data", 'updated');

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


/*

Form Events Init

*/
function formEventsInit() {
  onUninstall();
}

export {
  formEventsInit,
  unbindConnectForm,
  unbindDisconnectForm,
  formatConnectorFormData,
  formConnectionRules
};
