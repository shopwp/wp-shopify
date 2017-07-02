import currencyFormatter from 'currency-formatter';

import {
  animateOut
} from '../utils/utils-ux';

import {
  getCurrencyFormat,
  getMoneyFormat,
  getMoneyFormatWithCurrency
} from '../ws/ws-settings';


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


function extractMoneyFormatType(format) {

  var newFormat = format;
  newFormat = newFormat.split('{{').pop().split('}}').shift();

  return newFormat.replace(/\s+/g, " ").trim();

}

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





function replaceMoneyFormatWithRealAmount(formattedMoney, extractedMoneyFormat, moneyFormat) {

  var extractedMoneyFormat = new RegExp(extractedMoneyFormat, "g");
  var finalPrice = moneyFormat.replace(extractedMoneyFormat, formattedMoney);

  finalPrice = finalPrice.replace(/{{/g, '');
  finalPrice = finalPrice.replace(/}}/g, '');

  return finalPrice;

}


/*

Format product price into format from Shopify

1. Check whether we're using price_with_currency setting
2. Depending on the result of #1, either query the "money_format" or "money_with_currency_format" value
3. Once we have the value from #2, check what kind of variable we're using "amount, amount without decimal, etc".
4. Format our money accordingly
5. Replace the {{amount}} with our newly formatted money

*/
async function formatAsMoney(amount) {

  return new Promise(async function(resolve, reject) {

    /*

    STEP 1

    */
    try {
      var formatWithCurrencySymbol = await getCurrencyFormat();

    } catch (error) {
      reject(error);

    }

    /*

    STEP 2

    */
    if (formatWithCurrencySymbol) {

      try {
        var moneyFormat = await getMoneyFormatWithCurrency();

      } catch (error) {
        reject(error);

      }

    } else {

      try {
        var moneyFormat = await getMoneyFormat();

      } catch (error) {
        reject(error);

      }

    }

    var extractedMoneyFormat = extractMoneyFormatType(moneyFormat);
    var formattedMoney = formatMoneyPerSetting(amount, extractedMoneyFormat, moneyFormat);
    var finalPrice = replaceMoneyFormatWithRealAmount(formattedMoney, extractedMoneyFormat, moneyFormat);

    resolve(finalPrice);

  });

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
