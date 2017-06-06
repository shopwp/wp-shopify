/////////
// CSS //
/////////

import gulp from 'gulp';
import config from '../config';
import sourcemaps from 'gulp-sourcemaps';
import sass from 'gulp-sass';
import pleeease from 'gulp-pleeease';
import rename from "gulp-rename";

gulp.task('css-public', () => {
  return gulp.src(config.files.cssEntryPublic)
    .pipe(sourcemaps.init())
      .pipe(sass())
      .pipe(pleeease({
        "autoprefixer": "last 6 versions",
        "filters": true,
        "rem": true,
        "pseudoElements": true,
        "opacity": true,
        "calc": true,
        "import": true,
        "minifier": true,
        "mqpacker": false,
        "sourcemaps": false
      }))
      .pipe(rename(config.names.cssPublic))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(config.folders.cssPublic))
    .pipe(config.bs.stream());
});
