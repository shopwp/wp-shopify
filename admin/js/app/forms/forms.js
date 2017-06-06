import {
  enable,
  disable,
  hasVals
} from '../utils/utils';

import {
  delWebhooks,
  getWebhooks,
  addWebhook,
  getProductVariants,
  getProducts
} from '../ws/ws.js';

import {
  uninstallPluginData
} from '../disconnect/disconnect.js';


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

On Sync Data ...

*/
function onSyncData() {

  jQuery('.wps-btn-sync-data').on('click', async function syncDataHandler() {

    console.log('Beginning sync ...');
    var data = await syncPluginData();
    console.log('Done syncing ...', data);

  });
}


/*

On Webhook Add ...

*/
function onWebhookAdd() {
  jQuery('.wps-btn-wh-add').on('click', async function webhookAddHandler() {

    var resp = await addWebhook();
    console.log("Webhook get response: ", resp);

  });
}


/*

On Webhook Get ...

*/
function onWebhookGet() {
  jQuery('.wps-btn-wh-get').on('click', async function webhookGetHandler() {

    var resp = await getWebhooks();
    console.log("Webhook get response: ", resp);

  });
}


/*

On Webhook Del ...

*/
function onWebhookDelete() {
  jQuery('.wps-btn-wh-del').on('click', async function webhookDeleteHandler() {
    var resp = await delWebhooks();
    console.log("Webhook delete response: ", resp);
  });

}


/*

On plugin uninstall ...

*/
function onUninstall() {

  jQuery('.wps-btn-uninstall').on('click', async function uninstallHandler(event) {

    // uninstallPluginData();
    // console.log("getting variant ... ");
    // var result = await getProductVariants(7446023813);
    // console.log("result: ", result);

    console.log("getting variant ... ");
    // var result = await getProducts();
    console.log("result: ", result);


  });

}


/*

Remove event handlers on connection form ...

*/
function unbindConnectForm() {

  // console.log('Before: ', $._data(jQuery("#wps-connect").get(0), "events").submit);

  jQuery('#wps-connect').off("submit.connect");
  jQuery("#wps-connect").off('submit.validate');
  jQuery('#wps-connect').data('validator', null);

  // console.log('After: ', $._data(jQuery("#wps-connect").get(0), "events").submit);

}


/*

Remove event handlers on disconnection form ...

*/
function unbindDisconnectForm() {

  jQuery('#wps-connect').off("submit.disconnect");

}





function formatConnectorFormData(formData) {

  var formData = JSON.parse(JSON.stringify(formData));

  return formData.reduce(function(obj, item) {
    obj[item.name] = item.value;
    return obj;
  }, {} );

}







/*

Form Events Init

*/
function formEventsInit() {

  // onInputBlur();
  onSyncData();
  onWebhookAdd();
  onWebhookGet();
  onWebhookDelete();
  onUninstall();

}

export { formEventsInit, unbindConnectForm, unbindDisconnectForm, formatConnectorFormData };
