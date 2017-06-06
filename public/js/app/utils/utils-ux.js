import {
  listenForClose,
  removeEventHandlers,
  turnAnimationFlagOff,
  turnAnimationFlagOn,
  addOriginalClassesBack
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

  var $element = await animateIn(config);

  if(config.delay) {

    return setTimeout(function() {
      return animateOut($element, config.outClass);
    }, config.delay);

  } else {


  }

};


/*

Animate in

*/
function animateIn(config) {

  var classes = 'wps-is-visible wps-animated ' + config.inClass;
  console.log("config: ", config);
  config.originalClasses = config.element[0].className;

  turnAnimationFlagOn();

  return new Promise(function(resolve, reject) {
    config.element
      .addClass(classes)
      .one(animationClasses(), function(e) {

        turnAnimationFlagOff()
        config.element.removeClass(config.inClass);
        listenForClose(config);
        resolve(config.element);

      });
  });

};


/*

Animate out

*/
function animateOut(config) {

  var origClasses = config.element.data("origClasses");

  return new Promise(function(resolve, reject) {
    config.element
      .addClass(config.outClass)
      .one(animationClasses(), function afterAnimateOut(e) {

        turnAnimationFlagOff()
        addOriginalClassesBack(config);
        removeEventHandlers('wps-animated-element');

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

Show Notice

*/
// function showNotice(text, type) {
//   jQuery('.wps-notice').html(text).addClass( ('wps-notice-' + type) );
//   jQuery('html').animate({ scrollTop : 0 }, 200, toggleNotice);
// };


/*

Disable

*/
function disable($element) {
  // $element.prepend('<span class="spinner"></span>');
  $element.addClass('wps-is-disabled');
  $element.prop('disabled', true);
};


/*

Enable

*/
function enable($element) {
  setTimeout(function() {
    $element.removeClass('wps-is-disabled');
    $element.prop('disabled', false);
  }, 200);
};


/*

Show Loader

*/
function showLoader($element) {
  // console.log('hi');
  $element.addClass('wps-is-loading');
};


/*

Hide Loader

*/
function hideLoader($element) {
  // console.log('wee');
  $element.removeClass('wps-is-loading');
};


function shake($element) {

  animate({
    delay: 0,
		inClass: 'wps-shake',
		outClass: '',
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
  enable,
  showLoader,
  hideLoader,
  shake
}
