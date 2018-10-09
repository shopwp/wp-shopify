import { formEventsInit } from './forms/forms';
import { vendorInit } from './vendor/vendor';
import { tabsInit } from './utils/utils-tabs';
import { licenseInit } from './license/license';
import { connectInit, onAuthRedirect } from './connect/connect';
import { disconnectInit } from './disconnect/disconnect';
import { settingsInit } from './settings/settings.jsx';
import { initAdmin } from './admin/admin';
import { toolsInit } from './tools/tools';
import { initMisc } from './misc/misc';
import { menusInit } from './menus/menus';
import { noticesInit } from './notices/notices';

(function($) {

	'use strict';

	$(function() {

		initAdmin();
		tabsInit();
		vendorInit();
		formEventsInit();
		licenseInit();
		settingsInit();
		toolsInit();
		menusInit();
		noticesInit();
		initMisc();

		var $formConnect = $("#wps-connect");
	  var $submitButton = $formConnect.find('input[type="submit"]');

		if ($submitButton.attr('name') === 'submitDisconnect') {
			disconnectInit();

		} else {
			connectInit();
		}

  });

})(jQuery);
