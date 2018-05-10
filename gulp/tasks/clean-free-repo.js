/*

Clears out Free repo folder

*/
import gulp from 'gulp';
import config from '../config';
import del from 'del';

gulp.task('clean:free:repo', () => {
  return del([
    config.folders.freeRepo
  ], { force: true });
});
