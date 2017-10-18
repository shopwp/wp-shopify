import '../../css/app/app.scss';
import uuid from 'node-uuid';
import { formEventsInit } from './forms/forms';
import { vendorInit } from './vendor/vendor';
import { tabsInit } from './utils/utils-tabs';
import { licenseInit } from './license/license';
import { connectInit, onAuthRedirect } from './connect/connect';
import { disconnectInit } from './disconnect/disconnect';
import { settingsInit } from './settings/settings';
import { getUrlParams } from './utils/utils';
import { initAdmin } from './admin/admin';
import { toolsInit } from './tools/tools';


(function($) {
	'use strict';

	$(function() {

		var $formConnect = $("#wps-connect");
	  var $submitButton = $formConnect.find('input[type="submit"]');
		var params = getUrlParams(window.location.href);

		initAdmin();

		tabsInit();
		vendorInit();
		formEventsInit();
		licenseInit();
		settingsInit();
		toolsInit();

		if(params.auth && params.shop) {
			onAuthRedirect();

		} else {

			if($submitButton.attr('name') === 'submitDisconnect') {
				disconnectInit();

			} else {
				connectInit();

			}

		}


  });

})(jQuery);
