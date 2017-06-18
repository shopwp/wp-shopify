import { hasItemsInLocalStorage } from './ws-products';
import { renderCartItems, updateTotalCartPricing } from '../cart/cart-ui';

/*

Fetch Cart
Returns: Promise

*/
function fetchCart(shopify) {
  return shopify.fetchCart( localStorage.getItem('wps-last-cart-id') );
};


/*

Create Cart
Returns: Promise

*/
function createCart(shopify) {
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
      cart = await cart.createLineItemsFromVariants(options);
    } catch(error) {
      reject(error);
    }
    // console.log('cart before', cart);
    // console.log('cart after', cart);

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

  if(hasItemsInLocalStorage()) {
    // var cart = await fetchCart(shopify);

    try {
      var cart = await renderCartItems(shopify);

    } catch(error) {
      return error;
    }

    return cart;
    // showAllProducts(shopify);

  } else {

    try {
      var cart = await createCart(shopify);

    } catch(error) {
      return error;
    }

    setCart(cart);
    return cart;

    // showAllProducts(shopify);

  }

}

export { fetchCart, createCart, setCart, updateCart, initCart };
