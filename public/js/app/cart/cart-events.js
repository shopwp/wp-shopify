import { getProduct, getProductVariantID } from '../ws/ws-products';
import { animate, enable, disable, showLoader, hideLoader } from '../utils/utils-ux';
import { fetchCart, updateCart } from '../ws/ws-cart';
import { beforeCheckoutHook } from '../ws/ws-checkout';
import { quantityFinder } from '../utils/utils-common';
import { updateCartCounter, updateCartVariant, toggleCart } from './cart-ui';


/*

Checkout listener

*/
async function onCheckout(shopify) {

  try {

    var initialCart = await fetchCart(shopify);

    if(initialCart.lineItemCount === 0) {
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

      // Add the linker plugin to the anchor is GA is installed
      if (hasGA) {
        ga('linker:decorate', document.getElementById('wps-btn-checkout'));
      }

      try {

        var newCart = await fetchCart(shopify);

        if(!jQuery('.wps-btn-checkout').hasClass('wps-is-disabled')) {

        }

      } catch(e) {
        console.error('Error: fetchCart() 1: ', e);
        return e;
      }


      var cartData = {
        id: newCart.id,
        domain: newCart.config.domain,
        checkoutUrl: newCart.checkoutUrl
      };

      window.open(finalURL, '_self');

      // try {
      //   await beforeCheckoutHook(cartData);
      //
      //   var finalURL = newCart.checkoutUrl + '&attributes[keyy]=valuee';
      //
      //   console.log("finalURL: ", finalURL);
      //
      //   window.open(finalURL, '_self');
      //
      // } catch(e) {
      //   console.error('Error: beforeCheckoutHook() 1: ', e);
      //   return e;
      // }





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

    var quantity = parseInt(jQuery(this).val());
    var variantId = parseInt(jQuery(this).prev().attr('data-variant-id'), 10);
    var productId = parseInt(jQuery(this).prev().attr('data-product-id'), 10);
    var $cartForm = jQuery(this).closest('.wps-cart-item-container');

    disable($cartForm);
    disable(jQuery(this));

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


    try {
      await updateCart(variant, difference, shopify);

    } catch (error) {
      console.error('Error: updateCart() onManualQuantityChange()', error);

    }


    // Updates cart icon counter
    updateCartCounter(shopify);

    enable($cartForm);
    enable(jQuery(this));

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

    if(jQuery(this).hasClass('wps-quantity-increment')) {
      quantity = 1;

    } else {
      quantity = -1;
    }


    showLoader($cartForm);
    disable($cartForm);

    try {
      var product = await getProduct(shopify, productId);
      var variant = getProductVariantID(product, variantId);

    } catch(error) {
      console.error('EROR getProduct(): ', error);
      return error;
    }

    try {

      // Updates cart line item
      await updateCartVariant(variant, quantity, shopify);

      // Updates cart icon counter
      updateCartCounter(shopify);

    } catch(error) {
      console.error('EROR updateCartVariant(): ', error);
      return error;
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
