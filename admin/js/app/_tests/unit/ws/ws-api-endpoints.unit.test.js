import {
  endpointSettingAddToCartColor
} from '../../../ws/ws-api-endpoints';


it('Should return correct settings add to cart color endpoint', () => {

  const result = endpointSettingAddToCartColor();

  expect(result)
    .toBeString()
    .toEqual('http://wpstest.test/wp/api/wpshopify/v1/settings/add_to_cart_color');

});
