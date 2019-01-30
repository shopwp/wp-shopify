"use strict";

import "@babel/polyfill/noConflict";
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


jQuery( () => {

	initAdmin();
	tabsInit();
	vendorInit();
	formEventsInit();
	licenseInit();
	settingsInit();
	toolsInit();
	menusInit();
	initMisc();

	// Notices will only show on pages whitelisted by should_load_js()
	noticesInit();

});
