/*

Clears out build folders (used for debugging / productivity)

*/
import gulp from 'gulp';

gulp.task('clean:builds', done => {
  return gulp.parallel('clean:free', 'clean:pro', 'clean:free:repo', 'clean:tmp')(done);
});
