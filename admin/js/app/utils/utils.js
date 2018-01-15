import forEach from 'lodash/forEach';
import isURL from 'validator/lib/isURL';

import { stopSpinner } from './utils-dom';


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

Is WordPress Error

*/
function isConnected() {
  return jQuery('.wps-status-heading .wps-status').hasClass('is-connected');
}


/*

Remove true and transform to array

*/
function removeTrueAndTransformToArray(item) {

  var myArray = [];

  if (isObject(item)) {

    for (var key in item) {

      if (item[key] === true) {
        delete item[key];

      } else {
        myArray.push(item[key]);
      }

    }

    return myArray;

  } else {
    return item;

  }

}


/*

Check if value contains https:// or http://

*/
function containsDomain(value) {

  if( value.indexOf(".myshopify.com") === -1) {
    return false;

  } else {
    return true;

  }

}


/*

Check for HTTP(S)

*/
function containsProtocol(url) {

  if (url.indexOf("http://") > -1 || url.indexOf("https://") > -1) {
    return true;

  } else {
    return false;

  }

}


/*

If URL contains a trailing forward slash

*/
function containsTrailingForwardSlash(url) {

  if(url[url.length - 1] === '/') {
    return true;
  }

}


/*

Removes trailing forward slash

*/
function removeTrailingForwardSlash(url) {

  var newURL = url;
  newURL = newURL.slice(0, -1);

  return newURL;

}


/*

Check for HTTP(S)

*/
function containsURL(url) {

  var options = {
    protocols: ['http','https'],
    require_tld: true,
    require_protocol: true,
    require_host: true,
    require_valid_protocol: true,
    allow_underscores: false,
    host_whitelist: false,
    host_blacklist: false,
    allow_trailing_dot: false,
    allow_protocol_relative_urls: false
  };

  var validURL = isURL(url, options);

  if (validURL) {
    return true;

  } else {
    return false;

  }

}


/*

Check for additional characters after *.myshopify.com

*/
function containsPathAfterShopifyDomain(domain) {

  if (domain.indexOf("myshopify.com")) {

    var domainSplit = domain.split('myshopify.com');

    if (domainSplit.length > 1) {
      return true;
    }

  } else {
    return false;
  }

}


/*

Remove Protocol from string

*/
function cleanDomainURL(string) {

  var newString = string;

  if (newString.indexOf("http://") > -1) {
    newString = newString.replace("http://", "");
  }

  if (newString.indexOf("https://") > -1) {
    newString = newString.replace("https://", "");
  }

  if (newString.indexOf("myshopify.com/") > -1) {
    newString = newString.split('myshopify.com/');
    newString = newString[0] + 'myshopify.com';
  }

  return newString;

}


/*

Check if value is only alphanumeric

*/
function containsAlphaNumeric(value) {
  return value.match("^[a-zA-Z0-9]*$");
}


/*

Check if value exists

*/
function containsValue(value) {
  return value.length > 0;
}


/*

Util: Get URL Parameters
Returns: Object

*/
function getUrlParams(url) {

  var newURL = url;
  var urlParams = {};

  newURL.replace(
    new RegExp("([^?=&]+)(=([^&]*))?", "g"),
    function($0, $1, $2, $3) {
      urlParams[$1] = $3;
    }
  );

  return urlParams;

};


/*

Disable

*/
function disable($element) {
  $element.prop('disabled', true).attr('disabled', true);
}


/*

Enable

*/
function enable($element) {
  $element.prop('disabled', false).attr('disabled', false);
}


/*

Util: Enable buttons
Returns: $element without disable
TODO: Combine with the above

*/
function enableButton(button) {

  if (jQuery(button).is(':disabled')) {
    jQuery(button).prop('disabled', false);
  }

}


/*

Check for a value on a single element
Returns: Boolean

*/
function hasVal($input) {

   return $input.filter(function() {
     return jQuery(this).val();
   }).length > 0;

}


/*

Checks if all inputs have values
Returns: Boolean

*/
function hasVals($inputs) {

  var $emptyInputs = $inputs.filter(function() {
    return jQuery.trim(this.value) === "";
  });

  if(!$emptyInputs.length) {
    return true;

  } else {

    return false;

  }

}


/*

Show spinner

*/
function showSpinner(button) {

  jQuery(button).parent().next().addClass('wps-is-active');
  disable(jQuery(button));

};


/*

NEW: Show loader

*/
function showLoader($button) {

  $button.next().addClass('wps-is-active');
  disable($button);

};


/*

NEW: Hide loader

*/
function hideLoader($button) {

  $button.next().removeClass('wps-is-active');
  $button.prop("disabled", false);
  enable($button);

};


/*

Hide spinner

*/
function hideSpinner($element) {

  $element.parent().next().removeClass('wps-is-active');
  $element.prop("disabled", false);
  enable($element);

};


/*

Util: Reset the state of any UX indicators
Returns: undefined

*/
function resetProgressIndicators() {

  forEach(jQuery('.wps-admin-wrap .wps-spinner, .wps-connector-wrapper .wps-spinner'), stopSpinner);
  forEach(jQuery('.wps-admin-wrap .wps-btn, .wps-connector-wrapper .wps-btn, #submitConnect'), enableButton);

};


/*

Util: Disable buttons
Returns: $element with disable

*/
const disableButton = function(button) {

  if(jQuery(button).is(':enabled')) {
    jQuery(button).prop('disabled', true);
  }

};


/*

Getting nonce from localStorage

*/
function getNonce() {
  return localStorage.getItem("wps-nonce");
};


/*

Setting nonce into localStorage

*/
function setNonce(nonce) {
  localStorage.setItem('wps-nonce', nonce);
};


/*

Creates a masked version of a particular string

*/
function createMask(origString, mask, revealLength) {

  var origStringLength = origString.length;
  var lastFour = origString.substr(origStringLength - revealLength);
  var remaining = origString.slice(0, -revealLength);
  var remainingLength = remaining.length;
  var maskedKey = new Array(remaining.length + 1).join(mask) + lastFour;

  return maskedKey;

}


/*

Creates a masked version of a particular string

*/
function formatExpireDate(dateString) {

  var timestamp = Date.parse(dateString);

  if (isNaN(timestamp) == false) {
    var date = new Date(timestamp);
    return dateFormat(date, "mmmm d, yyyy");
  }


}

function getDataFromArray(array) {
  return array.map(function(item) {
    return item.data;
  });
}


export {
  getUrlParams,
  showSpinner,
  hideSpinner,
  disableButton,
  getNonce,
  setNonce,
  resetProgressIndicators,
  hasVal,
  hasVals,
  disable,
  enable,
  containsDomain,
  containsAlphaNumeric,
  containsValue,
  containsProtocol,
  containsURL,
  createMask,
  formatExpireDate,
  showLoader,
  hideLoader,
  cleanDomainURL,
  containsPathAfterShopifyDomain,
  containsTrailingForwardSlash,
  removeTrailingForwardSlash,
  removeTrueAndTransformToArray,
  isWordPressError,
  hasProp,
  isObject,
  getDataFromArray,
  isConnected
};
