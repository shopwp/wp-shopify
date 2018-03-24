import { getProduct, getProductVariantID, getCartID } from '../ws/ws-products';
import { animate, enable, disable, showLoader, hideLoader } from '../utils/utils-ux';
import { isEmptyCart } from '../utils/utils-cart';
import { fetchCart, updateCart } from '../ws/ws-cart';
import { quantityFinder, convertCustomAttrsToQueryString } from '../utils/utils-common';
import { updateCartCounter, updateCartVariant, toggleCart, renderEmptyCartMessage, emptyCartUI } from './cart-ui';
import { anyCustomAttrs } from '../ws/ws-checkout';

/*

Checkout listener

*/
function onCheckout(shopify) {

  return new Promise( async (resolve, reject) => {

    var finalCustomAttrs;

    /*

    Gets the cart instance ...

    */
    try {
      var initialCart = await fetchCart(shopify);

    } catch (error) {
      reject(error);
      return;
    }

    if (initialCart.lineItemCount === 0) {
      disable(jQuery('.wps-btn-checkout'));
    }

    // Checks to see if Google Analytics is installed
    if (window.ga === undefined) {
      var hasGA = false;

    } else {
      var hasGA = true;
    }


    /*

    When the user finally clicks the checkout button ...

    */
    jQuery('.wps-btn-checkout').on('click', async function checkoutHandler(event) {

      event.preventDefault();

      jQuery(document).trigger("wpshopify_checkout_before", [newCart]);


      if (jQuery('.wps-btn-checkout').hasClass('wps-is-disabled')) {
        return;
      }

      jQuery(this).addClass('wps-is-disabled wps-is-loading');


      // Add the linker plugin to the anchor if GA is installed
      if (hasGA) {
        ga('linker:decorate', document.getElementById('wps-btn-checkout'));
      }


      /*

      Step 1. Get cart instance

      */
      try {

        var newCart = await fetchCart(shopify);

      } catch(error) {
        jQuery(this).removeClass('wps-is-disabled wps-is-loading');
        reject(error);
        return;
      }


      try {
         var customAttrs = await anyCustomAttrs();

       } catch (error) {
         jQuery(this).removeClass('wps-is-disabled wps-is-loading');
         reject(error);
         return;
       }


       if (customAttrs.success) {
         finalCustomAttrs = convertCustomAttrsToQueryString(customAttrs.data);

       } else {
         finalCustomAttrs = '';
       }


       window.open(newCart.checkoutUrl + '&attributes[cartID]=' + getCartID() + finalCustomAttrs, '_self');


    });

    resolve();


  });

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
      console.error('WP Shopify Error: getProduct ', error);

    }

    var variant = getProductVariantID(product, variantId);

    try {
      var cart = await fetchCart(shopify);

    } catch (error) {
      console.error('WP Shopify Error: fetchCart ', error);

    }

    var difference = quantityFinder(cart.lineItems[0].quantity, quantity);


    /*

    Update cart model

    */
    try {
      var newCart = await updateCart(variant, difference, shopify);

    } catch (error) {
      console.error('WP Shopify Error: updateCart ', error);

    }


    /*

    Update cart counter

    */
    try {

      // Calls server if empty cart
      await updateCartCounter(shopify, newCart); //

    } catch (error) {
      console.error('WP Shopify Error: updateCartCounter ', error);
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

    // showLoader($cartForm);
    disable($cartForm);

    $cartForm.addClass('wps-is-disabled wps-is-loading');

    if (jQuery(this).hasClass('wps-quantity-increment')) {
      quantity = 1;

    } else {
      quantity = -1;
    }


    /*

    Get product

    */
    try {
      var product = await getProduct(shopify, productId);
      var variant = getProductVariantID(product, variantId);

    } catch(error) {
      console.error('WP Shopify Error getProduct: ', error);
      $cartForm.removeClass('wps-is-disabled wps-is-loading');
      return error;
    }


    /*

    Update cart variant

    */
    try {

      // Updates cart line item
      var newCart = await updateCartVariant(variant, quantity, shopify);

    } catch(error) {
      console.error('WP Shopify Error updateCartVariant: ', error);
      $cartForm.removeClass('wps-is-disabled wps-is-loading');
      return error;
    }


    /*

    Get cart instance

    */
    try {
      var newCart = await fetchCart(shopify);

    } catch(error) {
      console.error("WP Shopify Error fetchCart", error);
      $cartForm.removeClass('wps-is-disabled wps-is-loading');
      return error;
    }


    if ( isEmptyCart(newCart) ) {
      emptyCartUI(shopify, newCart);

    } else {

      // Updates cart icon counter
      updateCartCounter(shopify, newCart);

    }

    $cartForm.removeClass('wps-is-disabled wps-is-loading');
    // enable($cartForm);

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
