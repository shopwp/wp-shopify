/////////
// CSS //
/////////

import gulp from 'gulp';
import config from '../config';
import sass from 'gulp-sass';
import rename from "gulp-rename";
import postcss from 'gulp-postcss';
import gulpStylelint from 'gulp-stylelint';

gulp.task('css-public', () => {
  return gulp.src(config.files.cssEntryPublic)
    .pipe(sass())
    .pipe(gulpStylelint( config.stylelintConfig() ))
    .pipe(postcss( config.postCSSPlugins() ))
    .pipe(rename(config.names.cssPublic))
    .pipe(gulp.dest(config.folders.dist))
    .pipe(config.bs.stream());
});
