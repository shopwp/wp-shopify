import currencyFormatter from 'currency-formatter';
import forOwn from 'lodash/forOwn';
import isEmpty from 'lodash/isEmpty';

import {
  cartIsOpen
} from '../cart/cart-ui';

import {
  animateOut
} from '../utils/utils-ux';

import {
  getCurrencyFormat,
  getCurrencyFormats,
  getMoneyFormat,
  getMoneyFormatWithCurrency
} from '../ws/ws-settings';

import {
  setMoneyFormatCache,
  getMoneyFormatCache,
  moneyFormatChanged,
  getCacheTime,
  setCacheTime
} from '../ws/ws-products';

import {
  closeOptionsModal
} from '../products/products-meta';


/*

Is WordPress Error

*/
function isWordPressError(response) {

  if (isObject(response) && hasProp(response, 'success')) {

    if (response.success) {
      return false;

    } else {
      return true;

    }

  } else {
    return false;

  }

}


/*

Checks whether data is a WordPress error

*/
function isError(response) {

  if (isObject(response) && hasProp(response, 'success')) {

    if (response.success) {
      return false;

    } else {
      return true;

    }

  } else {
    return false;

  }

}


/*

Update

*/
function update(index, newVal, array) {

  var newArray = array;

  newArray.slice(0,index);
  newArray[index] = newVal;

  return newArray;

}


/*

Construct the custom attrs param

*/
function convertCustomAttrsToQueryString(customAttrs) {

  var finalQueryParam = '';

  forOwn(customAttrs, function(value, key) {
    finalQueryParam += '&attributes[' + encodeURIComponent(key) + ']=' + encodeURIComponent(value);
  });

  return finalQueryParam;

}


/*

Check if object has a property

*/
function hasProp(obj, prop) {
  return Object.prototype.hasOwnProperty.call(obj, prop);
}


/*

Check if an object

*/
function isObject(value) {
  var type = typeof value;
  return value != null && (type == 'object' || type == 'function');
}


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
  localStorage.setItem('wps-animating', true);
}


/*

Is Animation On?

*/
function isAnimating() {
  return localStorage.getItem('wps-animating');
}


/*

Extract Money Format Type

*/
function extractMoneyFormatType(format) {

  if (format) {
    var newFormat = format;
    newFormat = newFormat.split('{{').pop().split('}}').shift();

    return newFormat.replace(/\s+/g, " ").trim();

  } else {
    return false;
  }

}


/*

Format Money per settings

*/
function formatMoneyPerSetting(amount, format, origFormat) {

  if (format === 'amount') {

    var string = currencyFormatter.format(amount, {
      symbol: '',
      decimal: '.',
      thousand: ',',
      precision: 2,
      format: '%v'
    });

  } else if (format === 'amount_no_decimals') {

    amount = Number(amount);
    amount = Math.round(amount);

    var string = currencyFormatter.format(amount, {
      symbol: '',
      decimal: '.',
      thousand: ',',
      precision: 0,
      format: '%v'
    });

  } else if (format === 'amount_with_comma_separator') {

    var string = currencyFormatter.format(amount, {
      symbol: '',
      decimal: ',',
      thousand: ',',
      precision: 2,
      format: '%v'
    });

  } else if (format === 'amount_no_decimals_with_comma_separator') {

    amount = Number(amount);
    amount = Math.round(amount);

    var string = currencyFormatter.format(amount, {
      symbol: '',
      decimal: ',',
      thousand: ',',
      precision: 0,
      format: '%v'
    });

  } else if (format === 'amount_with_space_separator') {

    var string = currencyFormatter.format(amount, {
      symbol: '',
      decimal: ',',
      thousand: ' ',
      precision: 2,
      format: '%v'
    });

  } else if (format === 'amount_no_decimals_with_space_separator') {

    amount = Number(amount);
    amount = Math.round(amount);

    var string = currencyFormatter.format(amount, {
      symbol: '',
      decimal: ',',
      thousand: ' ',
      precision: 0,
      format: '%v'
    });

  } else if (format === 'amount_with_apostrophe_separator') {

    var string = currencyFormatter.format(amount, {
      symbol: '',
      decimal: '.',
      thousand: '\'',
      precision: 2,
      format: '%v'
    });

  } else {

    var string = currencyFormatter.format(amount, {
      symbol: '',
      decimal: '.',
      thousand: ',',
      precision: 2,
      format: '%v'
    });

  }

  return string;

}


/*

Replace money format with real amount

*/
function replaceMoneyFormatWithRealAmount(formattedMoney, extractedMoneyFormat, moneyFormat = '') {

  if (moneyFormat) {

    var extractedMoneyFormat = new RegExp(extractedMoneyFormat, "g");
    var finalPrice = moneyFormat.replace(extractedMoneyFormat, formattedMoney);

    finalPrice = finalPrice.replace(/{{/g, '');
    finalPrice = finalPrice.replace(/}}/g, '');

    return finalPrice;

  }

}



/*

Check if our LS cache has expired

*/
function cacheExpired() {

  var cachedTime = getCacheTime();

  if (isEmpty(cachedTime) || cachedTime === 'undefined') {
    return true;

  } else {

    var currentTime = new Date().getTime();
    var timeElapsedInSeconds = Math.floor((currentTime - parseInt(cachedTime)) / 1000);

    // Caching for 10mins
    if (timeElapsedInSeconds > 600) {
      return true;

    } else {
      return false;

    }

  }

}


/*

Format product price into format from Shopify

TODO: Expensive! Figure out how to speed up.

1. Check whether we're using price_with_currency setting
2. Depending on the result of #1, either query the "money_format" or "money_with_currency_format" value
3. Once we have the value from #2, check what kind of variable we're using "amount, amount without decimal, etc".
4. Format our money accordingly
5. Replace the {{amount}} with our newly formatted money

*/
async function formatAsMoney(amount) {

  return new Promise(async function(resolve, reject) {

    // Calls LS
    if (!cacheExpired()) {

      // Get the format from LS
      var moneyFormat = getMoneyFormatCache();

    } else {

      try {

        // Calls server
        var formats = await getCurrencyFormats(); // wps_get_currency_formats

        if (isError(formats)) {
          throw formats.data;

        } else {
          formats = formats.data;
        }

      } catch (error) {
        reject(error);

      }

      var formatWithCurrencySymbol = formats.priceWithCurrency;

      if (formatWithCurrencySymbol == '1') {

        var moneyFormat = formats.moneyFormatWithCurrency;

      } else {
        var moneyFormat = formats.moneyFormat;

      }

      // Calls LS
      setMoneyFormatCache(moneyFormat);

    }

    resolve( formatTotalAmount(amount, moneyFormat) );

  });

};


/*

Formats the total amount

*/
function formatTotalAmount(amount, moneyFormat) {

  var extractedMoneyFormat = extractMoneyFormatType(moneyFormat);
  var formattedMoney = formatMoneyPerSetting(amount, extractedMoneyFormat, moneyFormat);

  return replaceMoneyFormatWithRealAmount(formattedMoney, extractedMoneyFormat, moneyFormat);

}


/*

Listener: Close

*/
function listenForClose(config = false) {

  if (!config) {

    jQuery(document).on('click.wps-close-animation', closeCallbackClick);
    jQuery(document).on('keyup.wps-close-animation', closeCallbackEsc);

  } else {

    // if (!config.element.hasClass('wps-is-visible')) {
    //   config.element.addClass('wps-is-visible');
    // }

    if (!config.oneway) {

      // Close when user clicks outside modal ...
      jQuery(document).on('click.wps-close-animation', config, closeCallbackClick);

      // Close when user hits escape ...
      jQuery(document).on('keyup.wps-close-animation', config, closeCallbackEsc);

    }

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

Is Cart?

*/
function isCart($element) {
  return $element.hasClass('wps-cart') ? true : false;
}


/*

Callback: Close Click Callback

*/
function closeCallbackClick(event) {

  var config = event.data;

  if (!config) {
    closeOptionsModal();

  } else {

    var element = document.querySelector( createSelector(config.element.attr('class')) ),
        triggerAddToCart = jQuery(event.srcElement).hasClass('wps-add-to-cart'),
        triggerVariantSelect = jQuery(event.srcElement).hasClass('wps-product-style'),
        cartIsClosing = isCart(jQuery(element));

    if (triggerAddToCart || triggerVariantSelect && cartIsClosing) {

    } else {

      if (localStorage.getItem('wps-animating') === 'false') {

        if (jQuery(event.target).hasClass('wps-modal-close-trigger') ) {
          animateOut(config);

        } else {

          if (event.target !== config.element && !jQuery.contains(element, event.target)) {
            animateOut(config);
          }

        }

      } else {
        animateOut(config);
      }

    }

  }


};


/*

Callback: Close Esc Callback

TODO: The assumption here is that if we don't pass in any data to the callback
the originating event is for the variant dropdowns. We should decouple this.

*/
function closeCallbackEsc(event) {

  if (!event.data) {

    if (event.keyCode && event.keyCode == 27) {
      closeOptionsModal();
    }

  } else {

    if (localStorage.getItem('wps-animating') === 'false') {

      var config = event.data;

      if (event.keyCode && event.keyCode == 27) {
        animateOut(config);
      }

    }

  }

};


/*

Show Error

*/
function showError(error) {

  if (isObject(error) && hasProp(error, 'message') && hasProp(error, 'type')) {
    var newError = error;

  } else {
    var newError = {
      type: 'warning',
      message: error
    }
  }

  jQuery('.wps-btn-cart')
    .removeClass('wps-is-disabled wps-is-loading');

  jQuery('.wps-product-meta')
    .html('<p class="wps-notice-inline wps-notice-' + newError.type + '">' + newError.message + '</p>')
    .removeClass('wps-is-disabled wps-is-loading');

}


export {
  createSelector,
  formatAsMoney,
  listenForClose,
  removeEventHandlers,
  addOriginalClassesBack,
  turnAnimationFlagOff,
  turnAnimationFlagOn,
  quantityFinder,
  isError,
  isObject,
  hasProp,
  isAnimating,
  convertCustomAttrsToQueryString,
  update,
  isWordPressError,
  showError,
  formatTotalAmount
};
