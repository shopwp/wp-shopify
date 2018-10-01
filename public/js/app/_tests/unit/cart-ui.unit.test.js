import {
  hasCartTerms,
  cartTermsAccepted,
  checkoutConditionsMet,
  enableCheckoutButton,
  disableCheckoutButton
} from '../../cart/cart-ui';


/*

Reset state before each test

*/
beforeEach(() => {

  WP_Shopify.hasCartTerms = false;
  jQuery('#wps-terms-checkbox').remove();
  jQuery('.wps-btn-checkout').remove();
  
});


/*

Should return false cart terms state

*/
it('Should return false cart terms state', () => {

  var resultFalse = hasCartTerms();

  expect(resultFalse)
  .toBeBoolean()
  .toBeFalse();

});


/*

Should return true cart terms state

*/
it('Should return true cart terms state', () => {

  WP_Shopify.hasCartTerms = true;

  var resultTrue = hasCartTerms();

  expect(resultTrue)
  .toBeBoolean()
  .toBeTrue();

});


/*

Should return checked terms

*/
it('Should return checked terms', () => {

  // Inserts mock checkbox
  jQuery('body').append( jQuery('<input id="wps-terms-checkbox" checked>') );

  var propValue = cartTermsAccepted();

  expect(propValue)
    .toBeBoolean()
    .toBeTrue();

});


/*

Should return unchecked terms

*/
it('Should return unchecked terms', () => {

  // Inserts mock checkbox
  jQuery('body').append( jQuery('<input id="wps-terms-checkbox">') );

  var propValueFalse = cartTermsAccepted();

  expect(propValueFalse)
    .toBeBoolean()
    .toBeFalse();

});


/*

Cart term conditions not met

*/
it('Cart terms conditions NOT met', () => {

  WP_Shopify.hasCartTerms = true;
  jQuery('#wps-terms-checkbox').prop('checked', false);

  var checkoutConditionsMetResult = checkoutConditionsMet();

  expect(checkoutConditionsMetResult)
    .toBeBoolean()
    .toBeFalse();

});


/*

Cart term conditions met

*/
it('Cart terms conditions met', () => {

  WP_Shopify.hasCartTerms = true;

  // Inserts mock checkbox
  jQuery('body').append( jQuery('<input id="wps-terms-checkbox" checked>') );

  var checkoutConditionsMetResult = checkoutConditionsMet();

  expect(checkoutConditionsMetResult)
    .toBeBoolean()
    .toBeTrue();

});


/*

Cart term conditions met

*/
it('Should enable checkout button', () => {

  jQuery('body').append( jQuery('<a href="#" class="wps-btn-checkout wps-is-disabled wps-is-loading"></a>') );

  expect( jQuery('.wps-btn-checkout') )
    .toHaveClass('wps-is-disabled')
    .toHaveClass('wps-is-loading');

  enableCheckoutButton();

  expect( jQuery('.wps-btn-checkout') )
    .not.toHaveClass('wps-is-disabled')
    .not.toHaveClass('wps-is-loading');

});


/*

Cart term conditions met

*/
it('Should disable checkout button', () => {

  jQuery('body').append( jQuery('<a href="#" class="wps-btn-checkout"></a>') );

  expect( jQuery('.wps-btn-checkout') )
    .not.toHaveClass('wps-is-disabled')
    .not.toHaveClass('wps-is-loading');

  disableCheckoutButton();

  expect( jQuery('.wps-btn-checkout') )
    .toHaveClass('wps-is-disabled');

});
