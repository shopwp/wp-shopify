/*

Clears out pro version build folder (used for debugging / productivity)

*/
import gulp from 'gulp';
import config from '../config';
import del from 'del';

gulp.task('clean:pro', () => {
  return del([
    config.files.buildProContent
  ], { force: true });
});
