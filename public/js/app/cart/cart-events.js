import { getProduct, getProductVariantID, getCartID } from '../ws/ws-products';
import { animate, enable, disable, showLoader, hideLoader } from '../utils/utils-ux';
import { fetchCart, updateCart } from '../ws/ws-cart';
import { quantityFinder, convertCustomAttrsToQueryString } from '../utils/utils-common';
import { updateCartCounter, updateCartVariant, toggleCart, isCartEmpty, renderEmptyCartMessage, emptyCartUI } from './cart-ui';

import { anyCustomAttrs } from '../ws/ws-checkout';

/*

Checkout listener

*/
async function onCheckout(shopify) {

  var finalCustomAttrs;

  try {

    var initialCart = await fetchCart(shopify);

    if (initialCart.lineItemCount === 0) {
      disable(jQuery('.wps-btn-checkout'));
    }

    // Checks to see if Google Analytics is installed
    if (window.ga === undefined) {
      var hasGA = false;

    } else {
      var hasGA = true;
    }


    jQuery('.wps-btn-checkout').on('click', async function checkoutHandler(event) {

      event.preventDefault();

      if (jQuery('.wps-btn-checkout').hasClass('wps-is-disabled')) {
        return;
      }

      // Add the linker plugin to the anchor is GA is installed
      if (hasGA) {
        ga('linker:decorate', document.getElementById('wps-btn-checkout'));
      }

      try {
        var newCart = await fetchCart(shopify);

      } catch(e) {
        console.error('Error: fetchCart() 1: ', e);
        return e;
      }


      try {

        var customAttrs = await anyCustomAttrs();

      } catch (e) {
        console.error("errrr: ", e);
      }


      if (customAttrs.success) {
        finalCustomAttrs = convertCustomAttrsToQueryString(customAttrs.data);

      } else {
        finalCustomAttrs = '';
      }


      window.open(newCart.checkoutUrl + '&attributes[cartID]=' + getCartID() + finalCustomAttrs, '_self');

    });


  } catch(e) {
    console.error('Error: fetchCart() 2:  ', e);
    return e;

  }

};


/*

Toggle Cart

*/
function onToggleCart() {

  jQuery('.wps-btn-cart').on('click', async function toggleCartHandler(e) {

    e.preventDefault();
    toggleCart();

  });

}


/*

On Manual Quantity Change

*/
function onManualQuantityChange(shopify) {

  jQuery('.wps-cart').on('blur', '.wps-cart-item__quantity', async function quantityChangeHandler() {

    var $input = jQuery(this);
    var quantity = parseInt($input.val());
    var variantId = parseInt($input.prev().attr('data-variant-id'), 10);
    var productId = parseInt($input.prev().attr('data-product-id'), 10);
    var $cartForm = $input.closest('.wps-cart-item-container');

    disable($cartForm);
    disable($input);

    try {
      var product = await getProduct(shopify, productId);

    } catch(error) {
      console.error('Error: getProduct() onManualQuantityChange() ', error);

    }

    var variant = getProductVariantID(product, variantId);

    try {
      var cart = await fetchCart(shopify);

    } catch (error) {
      console.error('Error: fetchCart() onManualQuantityChange()', error);

    }

    var difference = quantityFinder(cart.lineItems[0].quantity, quantity);


    /*

    Update cart model

    */
    try {
      var newCart = await updateCart(variant, difference, shopify);

    } catch (error) {
      console.error('Error: updateCart() onManualQuantityChange()', error);

    }


    /*

    Update cart counter

    */
    try {
      await updateCartCounter(shopify, newCart);

    } catch (error) {
      console.error('Error: updateCartCounter() onManualQuantityChange()', error);
    }


    setTimeout(() => {
      enable($cartForm);
      enable(jQuery('.wps-cart-item__quantity'));
    }, 0);


  });

}


/*

Increase product variant quantity in cart
TODO: Combine with below function

*/
function onQuantityChange(shopify) {

  jQuery('.wps-cart').on('click', '.wps-quantity-increment, .wps-quantity-decrement', async function cartIncHandler() {

    var quantity;
    var $cartForm = jQuery(this).closest('.wps-cart-item-container');
    var variantId = parseInt(jQuery(this).attr('data-variant-id'), 10);
    var productId = parseInt(jQuery(this).attr('data-product-id'), 10);

    showLoader($cartForm);
    disable($cartForm);

    if (jQuery(this).hasClass('wps-quantity-increment')) {
      quantity = 1;

    } else {
      quantity = -1;
    }

    try {
      var product = await getProduct(shopify, productId);
      var variant = getProductVariantID(product, variantId);

    } catch(error) {
      console.error('EROR getProduct(): ', error);
      return error;
    }


    try {

      // Updates cart line item
      var newCart = await updateCartVariant(variant, quantity, shopify);

    } catch(error) {
      console.error('EROR updateCartVariant(): ', error);
      return error;
    }


    /*

    Get cart instance

    */
    try {
      var newCart = await fetchCart(shopify);
    } catch(error) {
      console.error("error", error);
    }


    if (isCartEmpty(newCart)) {
      emptyCartUI(shopify, newCart);

    } else {

      // Updates cart icon counter
      updateCartCounter(shopify, newCart);

    }

    enable($cartForm);

  });

};


/*

Initialize Cart Events

*/
function cartEvents(shopify) {
  onCheckout(shopify);
  onToggleCart();
  onQuantityChange(shopify);
  onManualQuantityChange(shopify);
}

export {
  cartEvents
};
