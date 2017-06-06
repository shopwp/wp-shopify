import { fetchCart } from '../ws/ws-cart';
import { getProduct, getProductVariantID } from '../ws/ws-products';
import { animate, enable, disable, showLoader, hideLoader } from '../utils/utils-ux';
import { updateCart } from '../ws/ws-cart';
import { quantityFinder } from '../utils/utils-common';
import { updateCartCounter, updateCartVariant, toggleCart } from './cart-ui';

/*

Checkout listener

*/
async function onCheckout(shopify) {

  var initialCart = await fetchCart(shopify);

  if(initialCart.lineItemCount === 0) {
    disable(jQuery('.wps-btn-checkout'));
  }

  jQuery('.wps-btn-checkout').on('click', async function checkoutHandler(event) {
    event.preventDefault();

    var newCart = await fetchCart(shopify);

    if(!jQuery('.wps-btn-checkout').hasClass('wps-is-disabled')) {
      window.open(newCart.checkoutUrl, '_self');
    }

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

    var quantity = parseInt(jQuery(this).val());
    var variantId = parseInt(jQuery(this).prev().attr('data-variant-id'), 10);
    var productId = parseInt(jQuery(this).prev().attr('data-product-id'), 10);
    var $cartForm = jQuery(this).closest('.wps-cart-item-container');

    disable($cartForm);
    disable(jQuery(this));

    var product = await getProduct(shopify, productId);
    var variant = getProductVariantID(product, variantId);
    var cart = await fetchCart(shopify);
    var difference = quantityFinder(cart.lineItems[0].quantity, quantity);
    var ok = await updateCart(variant, difference, shopify);

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
    var variantId = parseInt(jQuery(this).attr('data-variant-id'), 10);
    var productId = parseInt(jQuery(this).attr('data-product-id'), 10);
    var product = await getProduct(shopify, productId);
    var variant = getProductVariantID(product, variantId);
    var $cartForm = jQuery(this).closest('.wps-cart-item-container');

    showLoader($cartForm);
    disable($cartForm);
    disable(jQuery(this));

    if(jQuery(this).hasClass('wps-quantity-increment')) {
      quantity = 1;

    } else {
      quantity = -1;
    }

    // Updates cart line item
    await updateCartVariant(variant, quantity, shopify);

    // Updates cart icon counter
    updateCartCounter(shopify);

    // hideLoader($cartForm);
    enable($cartForm);
    // enable(jQuery(this));

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

export { cartEvents };
