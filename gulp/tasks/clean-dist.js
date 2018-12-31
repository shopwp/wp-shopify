/*

Clears out tmp folder

*/
import gulp from 'gulp';
import config from '../config';
import del from 'del';

gulp.task('clean:dist', done => {

  return del([
    config.files.buildProContent
  ], { force: true });

});
