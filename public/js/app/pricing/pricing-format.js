import has from 'lodash/has';
import trim from 'lodash/trim';
import currencyFormatter from 'currency-formatter';

import { hasCurrencyCode } from '../utils/utils-settings';
import { maybeTrimHTML } from '../utils/utils-common';
import { getMoneyFormat, getShop } from '../ws/ws-shop';
import { showingLocalCurrency, getLocalCurrencyCodeCache, showingCurrencyCode } from './pricing-currency';
import { convertToLocalCurrency } from './pricing-conversion';


function priceSymbolMarkup(symbol) {
  return '<span class="wps-product-price-currency" itemprop="priceCurrency">' + symbol + '</span>';
}

function priceAmountMarkup(amount) {
  return '<span itemprop="price" class="wps-product-individual-price">' + amount + '</span>';
}

function priceMarkup(parts, amount) {

  var priceMarkup = parts.map( ({type, value}) => {

    switch (type) {
      case 'currency': return priceSymbolMarkup(value) + priceAmountMarkup(amount);
      default : return value;
    }

  });

  return priceMarkup[0];

}


/*

locale currently not used

Config: {
  locale: 'en-US',
  countryCode: 'US',
  amount: 123,
}

*/
function formatPrice(config) {

  // Uses the browser locale by default
  if ( !has(config, 'locale') ) {
    config.locale = false;
  }

  var parts = new Intl.NumberFormat(config.locale, {
    style: 'currency',
    currency: config.countryCode,
    currencyDisplay: config.currencyDisplay
  }).formatToParts(config.amount);

  return priceMarkup(parts, config.amount);

}


/*

"price" should always be preformatted

*/
function formatPriceToLocalCurrency(price) {

  return formatPrice({
    countryCode: getLocalCurrencyCodeCache(),
    amount: price,
    currencyDisplay: showingCurrencyCode() ? 'code' : 'symbol'
  });

}



function formatPriceFromBase(price) {
  return formatTotalAmount(price, maybeTrimHTML( getMoneyFormat( getShop() ) ) );
}


/*

Format product price into format from Shopify

*/
function maybeFormatPriceToLocalCurrency(price) {

  if ( showingLocalCurrency() ) {
    return formatPriceToLocalCurrency(price);
  }

  return formatPriceFromBase(price);

}















/*

Comes from Shopify

*/
function maybeAddCurrencyCodeToMoney(formatWithRealAmount) {

  if ( hasCurrencyCode() ) {
    return formatWithRealAmount + ' ' + getShop().currencyCode;
  }

  return formatWithRealAmount;

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

Formats the total amount

*/
function formatTotalAmount(amount, moneyFormat) {

  var extractedMoneyFormat = extractMoneyFormatType(moneyFormat);
  var formattedMoney = formatMoneyPerSetting(amount, extractedMoneyFormat, moneyFormat);

  var formatWithRealAmount = replaceMoneyFormatWithRealAmount(formattedMoney, extractedMoneyFormat, moneyFormat);

  formatWithRealAmount = formatWithRealAmount.replace(/ /g,'');

  return maybeAddCurrencyCodeToMoney(formatWithRealAmount);

}


/*

Replace money format with real amount

*/
function replaceMoneyFormatWithRealAmount(formattedMoney, extractedMoneyFormat, moneyFormat = '') {

  if (moneyFormat) {

    var extractedMoneyFormat = new RegExp(extractedMoneyFormat, "g");
    var finalPrice = trim(moneyFormat).replace(extractedMoneyFormat, formattedMoney);

    finalPrice = finalPrice.replace(/{{/g, '');
    finalPrice = finalPrice.replace(/}}/g, '');

    return finalPrice;

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



export {
  formatPrice,
  maybeFormatPriceToLocalCurrency
}
