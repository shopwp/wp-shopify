/////////
// CSS //
/////////

import gulp from 'gulp';
import config from '../config';
import sourcemaps from 'gulp-sourcemaps';
import sass from 'gulp-sass';
import pleeease from 'gulp-pleeease';
import rename from "gulp-rename";

gulp.task('css-admin', () => {
  return gulp.src(config.files.cssEntryAdmin)
    .pipe(sourcemaps.init())
      .pipe(sass())
      .pipe(pleeease({
        "autoprefixer": true,
        "filters": true,
        "rem": true,
        "pseudoElements": true,
        "opacity": true,
        "import": true,
        "minifier": true,
        "mqpacker": false,
        "sourcemaps": false
      }))
      .pipe(rename(config.names.cssAdmin))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(config.folders.cssAdmin))
    .pipe(config.bs.stream());
});
