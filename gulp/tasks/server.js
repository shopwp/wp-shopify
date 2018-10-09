////////////
// Server //
////////////

import gulp from 'gulp';
import config from '../config';

gulp.task('server', (done) => {

  config.bs.init({
    proxy: config.serverName,
    notify: false
  });

  done();

});
