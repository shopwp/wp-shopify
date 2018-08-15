import { getErrorContents } from '../utils/utils-notices';
import { getCheckoutID } from './ws-products';

/*

Fires the on checkout action
Returns: Promise
TODO: Not currently used

*/
function beforeCheckoutHook(cart) {

  return new Promise((resolve, reject) => {

    const action_name = 'add_checkout_before_hook';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        cart: cart,
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}


/*

Fires the on checkout action
Returns: Promise

*/
function anyCustomAttrs(cart) {

  return new Promise((resolve, reject) => {

    const action_name = 'get_cart_checkout_attrs';

    jQuery.ajax({
      method: 'POST',
      url: WP_Shopify.ajax,
      dataType: 'json',
      data: {
        action: action_name,
        cartID: getCheckoutID(),
        nonce: WP_Shopify.nonce
      },
      success: data => resolve(data),
      error: (xhr, txt, err) => {
        reject( getErrorContents(xhr, err, action_name) );
      }
    });

  });

}





export {
  beforeCheckoutHook,
  anyCustomAttrs
}
