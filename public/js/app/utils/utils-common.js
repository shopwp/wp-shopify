import forOwn from 'lodash/forOwn';
import isEmpty from 'lodash/isEmpty';
import has from 'lodash/has';
import isError from 'lodash/isError';
import isNull from 'lodash/isNull';
import isUndefined from 'lodash/isUndefined';
import filter from 'lodash/filter';
import values from 'lodash/values';
import replace from 'lodash/replace';
import trim from 'lodash/trim';

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


function hasHTML(string) {
  return /<[a-z][\s\S]*>/i.test(string);
}

function removeHTML(html) {
  return jQuery(html).unwrap().html();
}

function maybeTrimHTML(moneyFormat) {

  if ( hasHTML(moneyFormat) ) {
    moneyFormat = removeHTML(moneyFormat);
  }

  return moneyFormat;

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


function swapDomains(stringToModify, domainOne, domainTwo) {
  return replace(stringToModify, domainOne, domainTwo);
}

/*

Element Exist

*/
function elementExists($element) {
  return $element.length;
}

export {
  createSelector,
  quantityFinder,
  isError,
  isObject,
  convertCustomAttrsToQueryString,
  update,
  containsInvalidLineItemProps,
  swapDomains,
  elementExists,
  maybeTrimHTML
}
