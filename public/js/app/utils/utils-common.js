import {
  animateOut
} from '../utils/utils-ux';


/*

Creates a queryable selector from a space
seperated list of class names.

*/
function createSelector(classname) {
  var newClass = classname;
  return "." + newClass.split(' ').join('.');
}


/*

removeEventHandlers

*/
function removeEventHandlers(elementClass) {
  jQuery(document).off('click.' + elementClass);
  jQuery(document).off('keyup.' + elementClass);
}


/*

addOriginalClassesBack

*/
function addOriginalClassesBack(config) {
  config.element.attr('class', config.originalClasses);
}


/*

turnAnimationFlagOff

*/
function turnAnimationFlagOff() {
  localStorage.setItem('wps-animating', false);
}


/*

turnAnimationFlagOn

*/
function turnAnimationFlagOn() {
  localStorage.setItem('wps-animating', false);
}


/*

Response Error

*/
function throwError(error) {
  console.info("You died, try again: ", error);
};


/*

Format number into dollar amount

*/
function formatAsMoney(amount) {
  return '$' + parseFloat(amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
};


/*

Listener: Close

*/
function listenForClose(config) {

  // console.log('Listening for close ...', config);

  if(!config.oneWay) {
    // Close when user clicks outside modal ...
    jQuery(document).on('click.wps-animated-element', config, closeCallbackClick);

    // Close when user hits escape ...
    jQuery(document).on('keyup.wps-animated-element', config, closeCallbackEsc);
  }

};


/*

Find product quantity based on what the user enters
and what is currently set.

*/
function quantityFinder(currentQuantity, quantityUserWants) {

  var difference;

  if (currentQuantity > quantityUserWants) {
    difference = currentQuantity - quantityUserWants;
    difference = -Math.abs(difference);

  } else {
    difference = quantityUserWants - currentQuantity;
  }

  return difference;

}


/*

Callback: Close Click Callback

*/
function closeCallbackClick(event) {

  console.log('Closing from click ...');

  var config = event.data,
      element = document.querySelector( createSelector(config.element.attr('class')) );
  // console.log(1);
  if (localStorage.getItem('wps-animating') === 'false') {
    // console.log(2, jQuery(event.target));
    if(jQuery(event.target).hasClass('wps-modal-close-trigger')) {
      // console.log(3);
      animateOut(config);
      // console.log(4);
    } else {
      // console.log(5);
      if (event.target !== config.element && !jQuery.contains(element, event.target)) {
        // console.log(6);
        animateOut(config);
        // console.log(7);
      }
    }
    // console.log(8);

  }

  // console.log(9);

};


/*

Callback: Close Esc Callback

*/
function closeCallbackEsc(event) {

  console.log('Closing from ESC ...');

  if (localStorage.getItem('wps-animating') === 'false') {
    var config = event.data;

    if (event.keyCode && event.keyCode == 27) {
      animateOut(config);
    }

  }

};


export {
  createSelector,
  throwError,
  formatAsMoney,
  listenForClose,
  removeEventHandlers,
  addOriginalClassesBack,
  turnAnimationFlagOff,
  turnAnimationFlagOn,
  quantityFinder
};
