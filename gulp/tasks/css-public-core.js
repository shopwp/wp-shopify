////////////////////////////
// CSS Public (core only) //
////////////////////////////

import gulp from 'gulp';
import config from '../config';
import sass from 'gulp-sass';
import rename from "gulp-rename";
import postcss from 'gulp-postcss';
import gulpStylelint from 'gulp-stylelint';

gulp.task('css-public-core', () => {
  return gulp.src(config.files.cssEntryPublicCore)
      .pipe(sass())
      .pipe(gulpStylelint( config.stylelintConfig() ))
      .pipe(postcss( config.postCSSPlugins() ))
      .pipe(rename(config.names.cssPublicCore))
    .pipe(gulp.dest(config.folders.dist))
    .pipe(config.bs.stream());
});
