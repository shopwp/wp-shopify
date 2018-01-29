import '../../css/app/app.scss';
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
import { menusInit } from './menus/menus';

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
		menusInit();
		
		if ($submitButton.attr('name') === 'submitDisconnect') {
			disconnectInit();

		} else {
			connectInit();
		}


  });

})(jQuery);
