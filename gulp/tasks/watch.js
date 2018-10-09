/////////////////
// Watch files //
/////////////////

import gulp from 'gulp';
import config from '../config';

function reload(done) {
  config.bs.reload();
  done();
}

gulp.task('watch', (done) => {

  // JS
  gulp.watch( config.files.js, gulp.series('js', reload) );

  // Public CSS
  gulp.watch( config.files.cssPublic, gulp.series('css-public', reload) );

  // Admin CSS
  gulp.watch( config.files.cssAdmin, gulp.series('css-admin', reload) );

});
