////////////////////////////
// CSS Public (grid only) //
////////////////////////////

import gulp from 'gulp';
import config from '../config';
import sass from 'gulp-sass';
import rename from 'gulp-rename';
import postcss from 'gulp-postcss';
import gulpStylelint from 'gulp-stylelint';

gulp.task('css-public-grid', () => {
  return gulp.src(config.files.cssEntryPublicGrid)
    .pipe(sass())
    .pipe(gulpStylelint( config.stylelintConfig() ))
    .pipe(postcss( config.postCSSPlugins() ))
    .pipe(rename(config.names.cssPublicGrid))
  .pipe(gulp.dest(config.folders.dist))
  .pipe(config.bs.stream());
});
