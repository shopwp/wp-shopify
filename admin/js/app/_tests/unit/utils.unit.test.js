import {
  containsProtocol,
  containsTrailingForwardSlash,
  containsDomain
} from '../../utils/utils';


it('Should test for protocol in URL', () => {

  var resultOneFalse = containsProtocol('www.test.com'),
      resultTwoTrue = containsProtocol('https://www.test.com');

  expect(resultOneFalse).toBe(false);
  expect(resultTwoTrue).toBe(true);

});


it('Should test for trailing forward slash', () => {

  var resultOneTrue = containsTrailingForwardSlash('www.test.com/'),
      resultTwoTrue = containsTrailingForwardSlash('https://www.test.com'),
      resultThreeFalse = containsTrailingForwardSlash(false),
      resultFourFalse = containsTrailingForwardSlash([]),
      resultFiveTrue = containsTrailingForwardSlash('www.test.com//');

  expect(resultOneTrue).toBe(true);
  expect(resultTwoTrue).toBe(false);
  expect(resultThreeFalse).toBe(false);
  expect(resultFourFalse).toBe(false);
  expect(resultFiveTrue).toBe(true);

});


it('Should test for myshopify domain', () => {

  var resultOneTrue = containsDomain('www.testshop.myshopify.com'),
      resultTwoFalse = containsDomain('www.testshop.myshopif.com'),
      resultThreeFalse = containsDomain('');

  expect(resultOneTrue).toBe(true);
  expect(resultTwoFalse).toBe(false);
  expect(resultThreeFalse).toBe(false);

});
