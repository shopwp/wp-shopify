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
import del from 'del';
import git from 'gulp-git';
import shell from 'gulp-shell';
import fs from 'fs';


/*

Copies all files and folders assigned to `config.files.all` to the _tmp dir

This does _not_ carry over the node_modules folder. The reason is becauase node
will resolve the nessesary dependencies by looking to the parent folder (which in
our case is the main plugin folder that _does_ have node_modules.

More info on node dependency resolution: https://nodejs.org/api/modules.html#modules_loading_from_node_modules_folders

*/
gulp.task('build:copyToTmp', (done) => {

  return gulp
    .src( config.files.all )
    .pipe( gulp.dest(config.folders.tmp) );

});


/*

Moves our temp folder to our assets folder before
renaming and zipping

*/
gulp.task('build:zip:copy:pro', (done) => {

  if (!config.isPro) {
    return done();
  }

  return gulp
    .src( config.files.tmp, { base: "./" } )
    .pipe( gulp.dest(config.folders.pro) );

});


/*

Renames our pro _tmp dir to the final zip folder name

*/
gulp.task('build:zip:rename:pro', (done) => {

  if (!config.isPro) {
    return done();
  }

  fs.rename(config.folders.proTmp, config.folders.proTmpRenamed, function (err) {

    if (err) {
      throw err;
    }

    done();

  });

});


/*

Zip up files found in assets/wp-shopify-pro
and move the .zip file into assets/wp-shopify-pro

*/
gulp.task('build:zip:pro', done => {

  if (!config.isPro) {
    return done();
  }

  return gulp
    .src( config.files.distProFiles )
    .pipe( zip( config.names.zips.pro ) )
    .pipe( gulp.dest(config.folders.pro) );

});


gulp.task('build:free:copy:tmp', (done) => {

  return gulp
    .src( config.files.onlyWorking )
    .pipe( gulp.dest(config.folders.tmp) );

});


gulp.task('build:free:repo:clone', (done) => {
  return git.clone('git@github.com:wpshopify/wp-shopify.git', { args: './_free' });
});


gulp.task('build:free:repo:copy', (done) => {

  return gulp
    .src(config.files.tmpAll)
    .pipe( gulp.dest(config.folders.freeRepo) );

});


gulp.task('build:free:repo:move', (done) => {

  return gulp
    .src(config.files.freeRepoFilesAll, { base: './' })
    .pipe( gulp.dest(config.folders.free) );

});

gulp.task('build:free:copy:readme', (done) => {

  return gulp
    .src(config.files.coreRepoReadme)
    .pipe(gulp.dest(config.folders.freeTmpRenamed));

});



gulp.task('build:free:mergeWithCore', (done) => {

  return gulp
    .src(config.files.tmpFreeFiles)
    .pipe(gulp.dest(config.folders.coreRepo));

});


/*

Runs preprocess
- gulp.src always refers to files within _tmp folder

*/
gulp.task('build:preprocess', (done) => {

  return gulp
    .src( config.files.toBeProcessedTmp, { base: "./" } )
    .pipe( preprocess({
      context: {
        NODE_ENV: config.buildTier
      }
    }) )
    .pipe(replace('<?php ?>', function(match, p1, offset, string) {
      console.log('\x1b[33m%s\x1b[0m', 'Notice: replaced ' + match + ' in file: ' + this.file.relative);
      return '';

    }))
    .pipe( gulp.dest("./") );

});


/*

Changes Plugin name
- gulp.src always refers to files within _tmp folder

*/
gulp.task('build:rename:plugin', done => {

  return gulp
    .src( config.files.entryTmp, { base: "./" } )
    .pipe(preprocess({
      context: {
        NODE_ENV: config.buildTier
      }
    }))
    .pipe(replace(config.names.pro, function(match, p1, offset, string) {

      if (config.buildTier === 'free') {
        console.log('\x1b[33m%s\x1b[0m', 'Notice: replaced ' + match + ' with WP Shopify in file: ' + this.file.relative);
        return config.names.free;

      } else {
        return match;
      }

    }))
    .pipe( gulp.dest("./") );

});


/*

Changes Plugin version
- gulp.src always refers to files within _tmp folder

*/
gulp.task('build:rename:version', done => {

  return gulp
    .src( config.files.versionLocations, { base: "./" } )
    .pipe(preprocess({
      context: {
        NODE_ENV: config.buildTier
      }
    }))
    .pipe(replace(config.currentRelease, function(match, p1, offset, string) {

      console.log('\x1b[33m%s\x1b[0m', 'Notice: replaced ' + match + ' with ' + config.buildRelease + ' in file: ' + this.file.relative);
      return config.buildRelease;

    }))
    .pipe( gulp.dest("./") );

});


/*

Changes the main plugin Class. Allows both plugins to be loaded simultanously

*/
gulp.task('build:rename:misc', done => {

  return gulp
    .src( config.files.pluginTitleSettings, { base: "./" } )
    .pipe(preprocess({
      context: {
        NODE_ENV: config.buildTier
      }
    }))
    .pipe(replace("'WP Shopify Pro', WPS_PLUGIN_TEXT_DOMAIN", function(match, p1, offset, string) {

      if (config.buildTier === 'free') {
        console.log('\x1b[33m%s\x1b[0m', 'Notice: replaced ' + match + ' with WP Shopify in file: ' + this.file.relative);
        return "'WP Shopify', WPS_PLUGIN_TEXT_DOMAIN";

      } else {
        return match;
      }

    }))
    .pipe( gulp.dest("./") );

});


/*

Ensures we comment out the test version number

*/
gulp.task('build:remove:testversion', done => {

  return gulp
    .src( config.files.pluginUpdateFunction, { base: "./" } )
    .pipe(preprocess({
      context: {
        NODE_ENV: config.buildTier
      }
    }))
    .pipe(replace("$new_version_number = '", function(match, p1, offset, string) {

      console.log('\x1b[33m%s\x1b[0m', 'Notice: Commented out ' + match + ' in file: ' + this.file.relative);
      return "// $new_version_number = '";

    }))
    .pipe( gulp.dest("./") );

});


/*

Runs tests for php via PHPUnit

*/
gulp.task('test:php', (done) => {

  return gulp
    .src(config.folders.plugin)
    .pipe( phpunit('/usr/local/bin/phpunit', {
      stopOnFailure: true
    }) );

});


/*

Runs tests for js via Jest

*/
gulp.task('test:js', (done) => {

  return gulp
    .src(config.folders.plugin)
    .pipe( shell([
      'npm run tests-client-unit-no-watch',
      'npm run tests-client-integration'
    ]));

});


/*

Runs all tests in parallel

*/
gulp.task('tests', done => {
  return gulp.parallel('test:php', 'test:js')(done);
});


/*

Removes various files / folders from free verson. At this point,
all preprocessing has finished so functions have been removed as well.

We're simply cleaning up files that the free version won't ever use.

*/
gulp.task('build:clear:free', done => {

  if (config.buildTier !== 'free') {
    return done();
  }

  return del(config.files.buildFreeClear, { force: true });

});


/*

Removes superfluous files / folders from dist copy

Currently only removes admin and public JavaScript app code -- leaves vendor files

Targets the _tmp folder

*/
gulp.task('build:clear:tmp:superfluous', done => {
  return del(config.files.superfluousTmp, { force: true });
});


/*

Zip up files in _tmp folder

Requires:
--tier=""
--release=""

*/
gulp.task('build:zip:deploy:pro', done => {

  if (!config.isPro) {
    return done();
  }

  var command = 'rsync --progress -avz -e "ssh -i /Users/andrew/.ssh/wps2018" /Users/andrew/www/wpstest/assets/wp-shopify-pro/' + config.names.zips.pro + ' arobbins@162.243.170.76:~';

  return childProcess.exec(command, function (err, stdout, stderr) {

    if (err !== null) {
      console.log('Error build:zip:deploy:pro: ', err);
      return;
    }

  });

});


/*

1. Logs into server
2. Removes existing release folder just in case
3. Creates new release directory
4. Moves zip file into the newly created directory
5. Changs .zip permissions to 755


ssh -tt arobbins@162.243.170.76

rm -rf /var/www/prod/html/pro/releases/1.3.2
mkdir  -p /var/www/prod/html/pro/releases/1.3.2
mv wp-shopify-pro.zip /var/www/prod/html/pro/releases/1.3.2
chmod 755 /var/www/prod/html/pro/releases/1.3.2/wp-shopify-pro.zip

*/
gulp.task('build:zip:move:pro', done => {

  if (!config.isPro) {
    return done();
  }

  var zipName = config.names.zips.pro;

  var command = 'ssh -tt -i /Users/andrew/.ssh/wps2018 arobbins@162.243.170.76 "rm -rf /var/www/prod/html/pro/releases/' + config.buildRelease + ' && mkdir  -p /var/www/prod/html/pro/releases/' + config.buildRelease + ' && mv ' + zipName + ' /var/www/prod/html/pro/releases/' + config.buildRelease + ' && chmod 755 /var/www/prod/html/pro/releases/' + config.buildRelease + '/' + zipName + '"';

  return childProcess.exec(command, function (err, stdout, stderr) {

    if (err !== null) {
      console.log('Error build:zip:move:pro ', err);
      return;
    }

  });

});


/*

Requires:
--tier=""
--release=""

*/
gulp.task('build:dist:pro', done => {

  return gulp.series(
    'build:zip:copy:pro', // Tmp -- copies all _tmp files to dist folder
    'build:zip:rename:pro', // Dist -- renames the _tmp folder to wp-shopify-pro
    'build:zip:pro', // Zip up files found in assets/wp-shopify-pro and move the .zip file into assets/wp-shopify-pro
    'build:zip:deploy:pro', // Copies the .zip file from local dist folder to the wpshop.io server ~
    'build:zip:move:pro' // Takes care of moving the .zip folder on the server to the correct place
  )(done);

});


/*

Zip up files in _tmp folder

Requires:
--tier=""
--release=""

*/
gulp.task('build:update:edd', done => {

  var tier = 'free';

  if (config.isPro) {
    tier = 'pro';
  }

  //
  // Prod build
  //
  var command = 'ssh -tt -i /Users/andrew/.ssh/wps2018 arobbins@162.243.170.76 "php -f /var/www/staging/html/wp-content/themes/wpshop/lib/updates/update-product-info.php ' + config.buildRelease + ' ' + tier +' && php -f /var/www/prod/html/wp-content/themes/wpshop/lib/updates/update-product-info.php ' + config.buildRelease + ' ' + tier + '"';

  //
  // Staging build
  //
  // var command = 'ssh -tt arobbins@162.243.170.76 "php -f /var/www/staging/html/wp-content/themes/wpshop/lib/updates/update-product-info.php ' + config.buildRelease + ' ' + tier + '"';

  return childProcess.exec(command, function (err, stdout, stderr) {

    if (err !== null) {
      console.log('Error build:zip:move:pro: ', err);
      return;
    }

  });


});




/*

Builds Pro Version

*/
gulp.task('build:assets', done => {
  return gulp.parallel('js', 'css-admin', 'css-public', 'css-public-core', 'css-public-grid', 'images-public', 'images-admin')(done);
});



/*

Builds Pro Version

*/
gulp.task('build:pro', done => {

  return gulp.series(
    'tests', // Non-tmp -- Runs all app tests
    'clean:tmp', // Non-tmp -- Removes the _tmp folder
    'clean:free:repo', // Non-tmp -- Removes ./_free folder
    'build:git', // Non-tmp -- Runs our git flow process (add / commit, release start, release pub, release finish)
    'build:copyToTmp', // Tmp -- Copies all app files to _tmp folder
    'build:preprocess', // Runs through preprocess code
    'build:rename:version', // Tmp -- Updates the version number within the main wp-shopify.php file and the config class
    'build:remove:testversion', // Tmp -- Ensures that the $new_version_number variable is commented out
    'build:assets', // Tmp
    'build:clear:tmp:superfluous', // Tmp - Removes uneeded files / folders from _tmp folder for the dist version (JavaScript app code, etc)
    'build:dist:pro', // takes app in assets dist folder, and zips them up, and sends to wpshop.io server
    'build:update:edd', // Logs into wpshop.io and runes the update-product-info.php file updates EDD settings
    'clean:tmp', // Deletes the _tmp folder
    'clean:dist',
    'build:git:cleanUp'
  )(done);

});


/*

Builds Free Version

*/
gulp.task('build:free', done => {

  return gulp.series(
    'tests', // Non-tmp -- Runs all app tests
    'clean:tmp', // Deletes the _tmp folder
    'clean:free:repo', // Deletes the _free folder
    'build:free:copy:tmp', // copies working files to new _tmp folder
    'build:preprocess', // removes non-free code within _tmp folder
    'build:rename:plugin', // Renames the @wordpress Plugin Name within the _tmp folder
    'build:rename:version', // Updates the version number within the main wp-shopify.php file and the config class
    'build:rename:misc', // Updates the title within _tmp/admin/partials/wps-admin-display.php
    'build:remove:testversion', // Ensures that the $new_version_number variable is commented out
    'build:clear:free', // removes non-free files / folders
    'build:assets',
    // 'build:free:repo:clone', // clones the free repo into the _free folder
    // 'build:free:repo:copy', // copies the files inside _tmp (which we just processed) into the _free folder
    // 'build:free:repo:move',
    // 'build:git', // Runs our git flow process (add / commit, release start, release pub, release finish)
    // 'build:free:copy:readme', // copies the core repo readme to the wpshopify/wpshopify folder
    // 'build:free:mergeWithCore', // merges _free files / folders with the wp-core folder in assets
    // 'clean:tmp', // Deletes the _tmp folder
    // 'clean:free:repo' // Deletes the _free folder
  )(done);

});


/*

Runs all build tasks

Requires:
--tier=""
--release=""

*/
gulp.task('build:prerelease', done => {

  return gulp.series(
    'tests',
    'clean:tmp',
    'clean:free:repo',
    'build:copyToTmp',
    'build:preprocess',
    'build:rename:plugin',
    'build:rename:version',
    'build:rename:misc',
    'build:remove:testversion',
    'build:assets',
    'build:clear:free',
    'build:clear:tmp:superfluous',
    'build:zip:pro'
  )(done);

});
