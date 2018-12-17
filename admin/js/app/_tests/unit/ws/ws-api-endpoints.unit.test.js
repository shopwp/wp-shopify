import {
  endpointSettingAddToCartColor
} from '../../../ws/api/api-endpoints';


it('Should return correct settings add to cart color endpoint', () => {

  const result = endpointSettingAddToCartColor();

  expect(result)
    .toBeString()
    .toEqual('http://wpshopify.loc/api/wpshopify/v1/settings/products_add_to_cart_color');

});
