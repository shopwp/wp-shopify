import {
  getPublishedProductIds
} from '../../ws/ws';


it('Should true check create checkmark', async () => {

  const arrayHasMoreThanOne = arr => arr.length > 1;
  const response = await getPublishedProductIds();

  expect(response)
    .toBeObject()
    .toContainAllKeys(['data', 'success'])
    .toContainEntry(['success', true])

  expect(response.data)
    .toBeArray()
    .toSatisfy(arrayHasMoreThanOne)

});
