///////////
// Build //
///////////

import gulp from 'gulp';
import preprocess from 'gulp-preprocess';
import config from '../config';
import phpunit from 'gulp-phpunit';
import jest from 'gulp-jest';
import replace from 'gulp-replace';
import zip from 'gulp-zip';
import flatten from 'gulp-flatten';
import rsync from 'gulp-rsync';
import childProcess from 'child_process';

/*

Copies all files and folders to _tmp dir

*/
gulp.task('build:copy', () => {

  return gulp
    .src( config.files.all )
    .pipe( gulp.dest(config.folders.tmp) );

});


/*

Runs preprocess
- gulp.src always refers to files within _tmp folder

*/
gulp.task('build:preprocess', () => {

  return gulp
    .src( config.files.toBeProcessed, { base: "./" } )
    .pipe(preprocess({
      context: {
        NODE_ENV: config.buildTier
      }
    }))
    .pipe(replace('<?php ?>', function(match, p1, offset, string) {
      console.log('\x1b[33m%s\x1b[0m', 'Notice: replaced ' + match + ' in file: ' + this.file.relative);
      return '';

    }))
    .pipe( gulp.dest("./") );

});


/*

Runs tests for php via PHPUnit

*/
gulp.task('test:php', () => {

  return gulp
    .src(config.folders.plugin)
    .pipe(phpunit('/usr/local/bin/phpunit', {
      stopOnFailure: true
    }));

});


/*

Runs tests for js via Jest

*/
gulp.task('test:js', () => {

  return gulp
  .src(config.folders.plugin)
  .pipe( jest(config.jestConfig() ));

});


/*

Runs all tests in parallel

*/
gulp.task('tests', done => {
  return gulp.parallel('test:php', 'test:js')(done);
});


/*

Zip up files in _tmp folder
Requires:
--tier=""
--release=""

*/
gulp.task('build:zip', done => {

  var zipName = config.buildTier === 'pro' ? 'wp-shopify-pro.zip' : 'wp-shopify.zip';

  return gulp
    .src(config.files.tmp)
    .pipe(zip(zipName))
    .pipe( gulp.dest(config.folders[config.buildTier]) );

});


/*

Zip up files in _tmp folder

Requires:
--tier=""
--release=""

*/
gulp.task('build:zip:deploy', done => {

  return childProcess.exec('rsync -avz /Users/arobbins/www/wpstest/assets/wp-shopify-pro/wp-shopify-pro.zip arobbins@162.243.170.76:~', function (err, stdout, stderr) {

    if (err !== null) {
      console.log('Error build:zip:deploy: ', err);
      return;
    }

  });

});


/*

Zip up files in _tmp folder

Requires:
--tier=""
--release=""

*/
gulp.task('build:zip:move', done => {

  return childProcess.exec('ssh -tt arobbins@162.243.170.76 "rm -rf /var/www/prod/html/pro/releases/' + config.buildRelease + ' && mkdir /var/www/prod/html/pro/releases/' + config.buildRelease + ' && mv wp-shopify-pro.zip /var/www/prod/html/pro/releases/' + config.buildRelease + ' && chmod 755 /var/www/prod/html/pro/releases/' + config.buildRelease + '/wp-shopify-pro.zip"', function (err, stdout, stderr) {

    if (err !== null) {
      console.log('Error build:zip:move: ', err);
      return;
    }

  });

});


/*

Requires:
--tier=""
--release=""

*/
gulp.task('build:dist', done => {

  return gulp.series(
    'build:zip',
    'build:zip:deploy',
    'build:zip:move'
  )(done);

});


/*

Runs all build tasks

Requires:
--tier=""
--release=""

*/
gulp.task('build', done => {

  return gulp.series(
    'tests', 'clean:tmp', 'build:copy', 'build:preprocess',
    gulp.parallel('js-admin', 'js-public', 'css-admin', 'css-public', 'css-public-core', 'css-public-grid', 'images-public', 'images-admin'),
    'build:dist'
  )(done);

});
