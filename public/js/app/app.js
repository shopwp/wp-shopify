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
