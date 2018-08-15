import {
  removeEventHandlers,
  turnAnimationFlagOff,
  turnAnimationFlagOn,
  addOriginalClassesBack,
  isAnimating
} from './utils-common';

import {
  clearAllCartNotices
} from './utils-notices';



/*

Toggle Notice

*/
function toggleNotice() {

	// animate({
  //   delay: 1500,
	// 	inClass: 'wps-bounceInDown',
	// 	outClass: 'wps-bounceOutUp',
  //   element: jQuery('.wps-notice')
	// });

}


/*

Disable

*/
function disable($element) {
  $element.addClass('wps-is-disabled');
  $element.prop('disabled', true);
}


/*

Disable No Loader

*/
function disableNoLoader($element) {
  $element.prop('disabled', true);
}


/*

Enable

*/
function enable($element) {
  $element.removeClass('wps-is-disabled wps-is-loading');
  $element.prop('disabled', false);
}


/*

Enable No Loader

*/
function enableNoLoader($element) {
  $element.prop('disabled', false);
}


/*

Show Loader

*/
function showLoader($element) {
  $element.addClass('wps-is-loading');
}


/*

Hide Loader

*/
function hideLoader($element) {
  $element.removeClass('wps-is-loading');
}


export {
  toggleNotice,
  disable,
  disableNoLoader,
  enable,
  enableNoLoader,
  showLoader,
  hideLoader
}
