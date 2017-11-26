import {
  listenForClose,
  removeEventHandlers,
  turnAnimationFlagOff,
  turnAnimationFlagOn,
  addOriginalClassesBack,
  isAnimating
} from './utils-common';


/*

Animation Classes

*/
function animationClasses() {
  return 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
}


/*

Animate wrapper

*/
async function animate(config) {

  // turnAnimationFlagOn();

  var $element = await animateIn(config);

  if (config.delay) {

    return setTimeout(function() {
      return animateOut($element, config.outClass);
    }, config.delay);

  }

};


/*

Animate in

*/
function animateIn(config) {

  var classes = 'wps-is-visible wps-animated ' + config.inClass;

  if (config.element[0] !== undefined) {
    config.originalClasses = config.element[0].className;

  } else {
    config.originalClasses = '';
  }

  return new Promise(function(resolve, reject) {

    config.element
      .addClass(classes)
      .one(animationClasses(), function(e) {

        turnAnimationFlagOff();

        if (!config.oneway) {
          listenForClose(config);
        }

        config.element.removeClass(config.inClass);
        resolve(config.element);

      });

  });

};


/*

Animate out

*/
function animateOut(config) {

  return new Promise(function(resolve, reject) {

    config.element
      .addClass(config.outClass)
      .one(animationClasses(), function afterAnimateOut(e) {

        turnAnimationFlagOff()
        addOriginalClassesBack(config);
        removeEventHandlers('wps-close-animation');

        config.element.removeClass('wps-fadeOut wps-is-visible');

        resolve(config.element);

      });
  });

};


/*

Toggle Notice

*/
function toggleNotice() {

	animate({
    delay: 1500,
		inClass: 'wps-bounceInDown',
		outClass: 'wps-bounceOutUp',
    element: jQuery('.wps-notice')
	});

};


/*

Disable

*/
function disable($element) {
  $element.addClass('wps-is-disabled');
  $element.prop('disabled', true);
};


/*

Disable No Loader

*/
function disableNoLoader($element) {
  $element.prop('disabled', true);
};


/*

Enable

*/
function enable($element) {
  $element.removeClass('wps-is-disabled');
  $element.prop('disabled', false);
};


/*

Enable No Loader

*/
function enableNoLoader($element) {
  $element.prop('disabled', false);
};


/*

Show Loader

*/
function showLoader($element) {
  $element.addClass('wps-is-loading');
};


/*

Hide Loader

*/
function hideLoader($element) {
  $element.removeClass('wps-is-loading');
};


/*

Shake

*/
function shake($element) {

  animateIn({
    delay: 0,
		inClass: 'wps-shake',
    oneway: true,
    element: $element
  });

}

export {
  animationClasses,
  animate,
  animateIn,
  animateOut,
  toggleNotice,
  disable,
  disableNoLoader,
  enable,
  enableNoLoader,
  showLoader,
  hideLoader,
  shake
}
