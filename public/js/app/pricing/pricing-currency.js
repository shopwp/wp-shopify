import geolocator from 'geolocator';
import codes from 'country-data';
import money from 'money';
import accounting from 'accounting';
import to from 'await-to-js';
import { getCache, setCache } from '../cache/cache';

import { changeAllPricingToLocal } from '../products/products-ui';
import { getCurrencyCodeByLocationFallback, countryBlacklisted } from './pricing-fallbacks';
import { maybeConvertPriceToLocalCurrency } from './pricing-conversion';
import { maybeFormatPriceToLocalCurrency } from './pricing-format';



function showingLocalCurrency() {
  return WP_Shopify.settings.pricing.enableLocalCurrency;
}

function baseCurrency() {
  return WP_Shopify.settings.pricing.baseCurrencyCode;
}

function showingCurrencyCode() {
  return WP_Shopify.settings.hasCurrencyCode;
}

function getCountryCodeFromLocation(location) {
  return codes.lookup.countries( {name: location.address.country} )[0];
}


/*

Important: Re

*/
function getCurrencyCodeFromLocation() {

  var location = JSON.parse( getLocationCache() );
  var currencyCode = getCountryCodeFromLocation(location).currencies[0];

  setLocalCurrencyCodeCache(currencyCode);

  return currencyCode;

}

function getCurrencyCodeByLocation(location) {

  const country = getCountryCodeFromLocation(location);

  if (!country) {
    return getCurrencyCodeByLocationFallback(location);
  }

  return country.currencies[0];

}

function getLocationCache() {
  return getCache('wpshopify-geo-location');
}

function getPricingRatesCache() {
  return getCache('wpshopify-pricing-rates');
}

function getLocalCurrencyCodeCache() {
  return getCache('wpshopify-pricing-local-currency-code');
}

function setLocalCurrencyCodeCache(currencyCode) {
  return setCache('wpshopify-pricing-local-currency-code', currencyCode);
}

function localCurrency() {

  const cachedCode = getLocalCurrencyCodeCache();

  if ( cachedCode ) {
    return cachedCode;
  }

  return getCurrencyCodeFromLocation();

}

function exchangeEndpoint(baseCode) {
  return 'https://api.exchangeratesapi.io/latest?base=' + baseCode;
}


function getRates() {

  return new Promise( async (resolve, reject) => {

    var [ratesError, ratesData] = await to( getExchangeRates( exchangeEndpoint( baseCurrency() ) ) );

    if (ratesError || !ratesData) {
      reject(ratesError);
    }

    var [ratesDataJSONError, ratesDataJSON] = await to( ratesData.json() );

    if (ratesDataJSONError) {
      reject(ratesDataJSONError);
    }

    setCache('wpshopify-pricing-rates', JSON.stringify(ratesDataJSON) );

    resolve(ratesDataJSON);

  });

}



function getExchangeRates(endpoint) {
  return fetch(endpoint);
}


function setRates(ratesDataJSON) {
  return money.rates = ratesDataJSON.rates;
}


function getAndSetRates() {

  return new Promise( async (resolve, reject) => {

    var [ratesError, ratesData] = await to( getRates() );

    if (ratesError) {
      return reject(ratesError);
    }

    setRates(ratesData);

    return resolve(ratesData);

  });

}


function getAndSetLocation() {

  return new Promise( (resolve, reject) => {

    geolocator.locateByIP(false, function (err, location) {

      if (err) {
        return reject(err);
      }

      // If we can't hit the API becuase the country is blacklisted, default to base currency
      if ( countryBlacklisted(location.address.country) ) {

        console.info('WP Shopify ℹ️ Country is blacklisted. Defaulting to the Shop\'s base currency instead.');
        setLocalCurrencyCodeCache( baseCurrency() );

        return resolve( baseCurrency() );

      } else {

        setLocalCurrencyCodeCache( getCurrencyCodeByLocation(location) );

      }

      setCache('wpshopify-geo-location', JSON.stringify(location) );

      return resolve(location);

    });

  });

}


function localCurrencyReady() {

  if ( getLocationCache() && getPricingRatesCache() ) {
    return true;
  }

  return false;

}

/*

Get Deselected Dropdowns

Both Shopify and the 'country-data' lib uses ISO 4217 for their currency code under the Shop endpoint

*/
function bootstrapLocalCurrencyRequirements() {

  if ( !localCurrencyReady() ) {

    return new Promise( async (resolve, reject) => {

      var [ getAndSetLocationError, getAndSetLocationData ] = await to( getAndSetLocation() );

      if (getAndSetLocationError || getAndSetLocationData === baseCurrency() ) {
        return resolve([getAndSetLocationData, false]);
      }


      var [ getAndSetRatesError, getAndSetRatesData ] = await to( getAndSetRates() );

      if (getAndSetRatesError) {
        reject(getAndSetRatesError);
      }

      resolve([getAndSetLocationData, getAndSetRatesData]);

    });

  }

  setRates( JSON.parse( getPricingRatesCache() ) );

  return Promise.resolve( JSON.parse( getPricingRatesCache() ) );

}


function convertAndFormatPrice(price) {
  return maybeFormatPriceToLocalCurrency( maybeConvertPriceToLocalCurrency(price) );
}


export {
  bootstrapLocalCurrencyRequirements,
  showingLocalCurrency,
  getLocalCurrencyCodeCache,
  getCurrencyCodeByLocation,
  baseCurrency,
  localCurrency,
  showingCurrencyCode,
  convertAndFormatPrice
}
