"use strict";

import "idempotent-babel-polyfill";
import "@wordpress/components/build-style/style.css";

import { formEventsInit } from './forms/forms';
import { vendorInit } from './vendor/vendor';
import { tabsInit } from './utils/utils-tabs';
import { licenseInit } from './license/license';
import { settingsInit } from './settings/settings.jsx';
import { initAdmin } from './admin/admin';
import { toolsInit } from './tools/tools';
import { initMisc } from './misc/misc';
import { menusInit } from './menus/menus';
import { noticesInit } from './notices/notices';

_.noConflict();

jQuery( () => {

	if ( jQuery('body').hasClass('wp-shopify_page_wps-settings') ) {
		initAdmin();
		tabsInit();
		vendorInit();
		formEventsInit();
		licenseInit();
		settingsInit();
		toolsInit();
		menusInit();
		initMisc();
	}

	noticesInit();

});
