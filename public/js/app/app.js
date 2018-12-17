import 'core-js/fn/promise';
import 'core-js/fn/object/assign';
import 'core-js/fn/string/ends-with';
import 'core-js/fn/string/starts-with';
import 'core-js/fn/string/includes';
import 'core-js/fn/array/includes';
import 'core-js/fn/array/find';

import to from 'await-to-js';
import bootstrap from "./utils/utils-bootstrap";

import { showGlobalNotice } from "./utils/utils-notices";

(function($) {

  "use strict";

  $(async () => {

    const [ error, response ] = await to( bootstrap() );

    if (error) {
      showGlobalNotice(error, 'error');
    }

  });

}(jQuery));
