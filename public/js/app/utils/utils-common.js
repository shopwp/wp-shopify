import currencyFormatter from 'currency-formatter';
import forOwn from 'lodash/forOwn';
import isEmpty from 'lodash/isEmpty';
import has from 'lodash/has';
import isError from 'lodash/isError';
import isNull from 'lodash/isNull';
import isUndefined from 'lodash/isUndefined';
import filter from 'lodash/filter';
import values from 'lodash/values';


import {
  cartIsOpen
} from '../cart/cart-ui';

import {
  enable
} from '../utils/utils-ux';

import {
  getClient
} from '../utils/utils-client';

import {
  setMoneyFormatCache,
  getCacheTime,
  setCacheTime
} from '../ws/ws-products';

import {
  getMoneyFormat,
  getShop
} from '../ws/ws-shop';



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

*/
function formatAsMoney(amount) {
  return formatTotalAmount(amount, getMoneyFormat(getShop()) );
}


/*

Formats the total amount

*/
function formatTotalAmount(amount, moneyFormat) {

  var extractedMoneyFormat = extractMoneyFormatType(moneyFormat);
  var formattedMoney = formatMoneyPerSetting(amount, extractedMoneyFormat, moneyFormat);

  return replaceMoneyFormatWithRealAmount(formattedMoney, extractedMoneyFormat, moneyFormat);

}


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

Checks if data is an invalid value for add or updating lineitems

*/
function invalidLineItemProp(lineItemProp) {

  if ( isError(lineItemProp) || isNull(lineItemProp) || isUndefined(lineItemProp) || lineItemProp === false) {
    return true;
  }

}


/*

Checks if config for add or updating lineitems is valid

*/
function containsInvalidLineItemProps(lineItemProps) {
  return !isEmpty( filter( values(lineItemProps), invalidLineItemProp) );
}


export {
  createSelector,
  formatAsMoney,
  quantityFinder,
  isError,
  isObject,
  convertCustomAttrsToQueryString,
  update,
  formatTotalAmount,
  containsInvalidLineItemProps
};
