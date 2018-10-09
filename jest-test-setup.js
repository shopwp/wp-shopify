import 'jest-extended';
import 'jest-chain';
import * as matchers from 'jest-jquery-matchers';

// this is basically: afterEach(cleanup)
import 'react-testing-library/cleanup-after-each'

jest.addMatchers(matchers);

expect.extend({

  toBejQueryObject(received, floor, ceiling) {

    const pass = received instanceof jQuery;

    if (pass) {

      return {
        message: () =>
          `expected ${received} not to be a jQuery Object`,
        pass: true,
      }

    } else {

      return {
        message: () =>
          `expected ${received} to be a jQuery Object`,
        pass: false,
      }

    }

  }

});

HTMLCanvasElement.prototype.getContext = () => {}
