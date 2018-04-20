/*

Clears out free version build folder (used for debugging / productivity)

*/
import gulp from 'gulp';
import config from '../config';
import del from 'del';

gulp.task('clean:free', () => {
  return del([
    config.files.buildFreeContent
  ], { force: true });
});
