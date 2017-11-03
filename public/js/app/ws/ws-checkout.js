/*

Fires the on checkout action
Returns: Promise

*/
function beforeCheckoutHook(cart) {

  return jQuery.ajax({
    method: 'POST',
    url: wps.ajax,
    dataType: 'json',
    data: {
      action: 'wps_add_checkout_before_hook',
      cart: cart
    }
  });

};

export {
  beforeCheckoutHook
};
