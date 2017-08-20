import 'whatwg-fetch';
import { needsCacheFlush, flushCache } from '../utils/utils-cart';
import { hasItemsInLocalStorage } from './ws-products';
import { renderCartItems, updateTotalCartPricing } from '../cart/cart-ui';

/*

Fetch Cart
Returns: Promise

*/
async function fetchCart(shopify) {

  if (!shopify) {
    return false;
  }

  // Either get the current cart instance or create a new one
  try {
    var cart = await shopify.fetchCart(localStorage.getItem('wps-last-cart-id'));

  } catch(e) {
    var cart = await createCart(shopify);

  }

  return cart ? cart : false;

};


/*

Create Cart
Returns: Promise

*/
async function createCart(shopify) {
  return shopify.createCart();
}


/*

Set cart items

*/
function setCart(cart) {
  localStorage.setItem('wps-last-cart-id', cart.id);
}


/*

Add 'quantity' amount of product 'variant' to cart

*/
function updateCart(variant, quantity, shopify) {

  return new Promise(async function(resolve, reject) {

    var options = {
      variant: variant,
      quantity: quantity
    };

    try {
      var cart = await fetchCart(shopify);

    } catch(error) {
      reject(error);
    }

    try {
      await cart.createLineItemsFromVariants(options);

    } catch(error) {
      reject(error);
    }

    renderCartItems(shopify);
    updateTotalCartPricing(shopify);
    // updateCartTabButton(shopify);

    resolve('Done updating cart UI');

  });

};














/*

Initialize Cart

*/
async function initCart(shopify) {

  if (hasItemsInLocalStorage() && !needsCacheFlush()) {

    // var cart = await fetchCart(shopify);

    try {
      var cart = await renderCartItems(shopify);

    } catch(error) {
      return error;
    }

    // showAllProducts(shopify);

  } else {

    try {
      var cart = await createCart(shopify);

    } catch(error) {
      return error;
    }

    setCart(cart);
    flushCache();
    // showAllProducts(shopify);

  }

  updateTotalCartPricing(shopify);

  return cart;

}

export { fetchCart, createCart, setCart, updateCart, initCart };
