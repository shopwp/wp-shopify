///////////////////////////
// Reload browser Server //
///////////////////////////

import config from '../config';

export default function reload(done) {
  config.bs.reload();
  done();
}
