////////////
// Config //
////////////

import argvs from 'yargs';
import webpack from 'webpack';
import UglifyJsPlugin from 'uglifyjs-webpack-plugin';
import browserSync from 'browser-sync';
import willChange from 'postcss-will-change';
import willChangeTransition from 'postcss-will-change-transition';
import mqpacker from 'css-mqpacker';
import colormin from 'postcss-colormin';
import cssstats from 'postcss-cssstats';
import cssnano from 'cssnano';
import autoprefixer from 'autoprefixer';
import presetEnv from 'postcss-preset-env';
import Visualizer from 'webpack-visualizer-plugin';
import ParallelUglifyPlugin from 'webpack-parallel-uglify-plugin';
import ProgressBarPlugin from 'progress-bar-webpack-plugin';
import path from 'path';

/*

Main Config Object

*/
var config = {

  files: {
    entry: './_tmp/wp-shopify.php',
    versionLocations: [
      './_tmp/wp-shopify.php',
      './_tmp/classes/class-config.php'
    ],
    toBeProcessed: [
      './_tmp/admin/js/**/*',
      './_tmp/admin/partials/**/*',
      './_tmp/public/js/**/*',
      './_tmp/public/templates/**/*',
      './_tmp/classes/**/*',
      './_tmp/wp-shopify.php',
      './_tmp/uninstall.php'
    ],
    tmp: './_tmp/**/*',
    all: [
      './**/*',
      '!./node_modules/**',
      '!./bin/**',
      '!./.git/**',
      '!./tests/**',
      '!./gulp/**',
      '!./stats.html',
      '!./.travis.yml',
      '!./.eslintrc',
      '!./**/*.DS_Store',
      '!./**/*.babelrc',
      '!./admin.min.js',
      '!./package.json',
      '!./phpunit.xml.dist',
      '!./postcss.config.js',
      '!./public.min.js',
      '!./gulpfile.babel.js'
    ],
    build: './**/*',
    buildProContent: '../../../../assets/wp-shopify-pro/**/*',
    buildFreeContent: '../../../../assets/wp-shopify/**/*',
    buildFreeClear: [
      './_tmp/webhooks',
      './_tmp/classes/class-webhooks.php',
      './_tmp/classes/class-orders.php',
      './_tmp/classes/class-customers.php'
    ],
    buildZip: argvs.argv.tier === 'free' ? '/Users/arobbins/www/wpstest/assets/wp-shopify/wp-shopify.zip' : '/Users/arobbins/www/wpstest/assets/wp-shopify-pro/wp-shopify-pro.zip',
    buildRoot: argvs.argv.tier === 'free' ? '/Users/arobbins/www/wpstest/assets/wp-shopify' : '/Users/arobbins/www/wpstest/assets/wp-shopify-pro',
    buildEntry: [
      './admin/js/app/tools/tools.js',
      './admin/partials/wps-tab-content-tools.php'
    ],
    jsPublic: [ // doesnt need tmp check
      './public/js/app/**/*.js',
      '!./public/js/app.min.js',
      '!./public/js/vendor.min.js',
      '!./public/js/app.min.js.map'
    ],
    jsAdmin: [ // doesnt need tmp check
      './admin/js/app/**/*.js',
      '!./admin/js/app.min.js',
      '!./admin/js/vendor.min.js',
      '!./admin/js/app.min.js.map'
    ],
    jsEntryPublic: argvs.argv.tier ? './_tmp/public/js/app/app.js' : './public/js/app/app.js',
    jsEntryAdmin: argvs.argv.tier ? './_tmp/admin/js/app/app.js' : './admin/js/app/app.js',
    cssPublic: './public/css/**/*.scss', // doesnt need tmp check
    cssEntryPublic: argvs.argv.tier ? './_tmp/public/css/app/app.scss' : './public/css/app/app.scss',
    cssEntryPublicCore: argvs.argv.tier ? './_tmp/public/css/app/core.scss' : './public/css/app/core.scss',
    cssEntryPublicGrid: argvs.argv.tier ? './_tmp/public/css/app/grid.scss' : './public/css/app/grid.scss',
    cssAdmin: './admin/css/**/*.scss', // doesnt need tmp check
    cssEntryAdmin: argvs.argv.tier ? './_tmp/admin/css/app/app.scss' : './admin/css/app/app.scss',
    svgsPublic: argvs.argv.tier ? './_tmp/public/imgs/**/*.svg' : './public/imgs/**/*.svg',
    svgsAdmin: argvs.argv.tier ? './_tmp/admin/imgs/**/*.svg' : './admin/imgs/**/*.svg'
  },
  folders: {
    tmp: './_tmp',
    plugin: './',
    dist: argvs.argv.tier ? './_tmp/dist' : './dist',
    pro: '../../../../assets/wp-shopify-pro',
    free: '../../../../assets/wp-shopify',
    svgsPublic: argvs.argv.tier ? './_tmp/public/imgs' : './public/imgs',
    svgsAdmin: argvs.argv.tier ? './_tmp/admin/imgs' : './admin/imgs',
    cache: './node_modules/.cache'
  },
  names: {
    jsVendorPublic: 'public.vendor.min.js',
    jsVendorAdmin: 'admin.vendor.min.js',
    jsPublic: 'public.min.js',
    cssPublic: 'public.min.css',
    cssPublicCore: 'core.min.css',
    cssPublicGrid: 'grid.min.css',
    jsAdmin: 'admin.min.js',
    cssAdmin: 'admin.min.css'
  },
  bs: browserSync.create(),
  serverName: 'wpstest.test',
  isBuilding: argvs.argv.tier ? false : false,
  buildTier: argvs.argv.tier ? argvs.argv.tier : false, // Build type can be either 'free' or 'pro'
  buildRelease: argvs.argv.release ? argvs.argv.release : false // Build type can be either 'free' or 'pro'
};


/*

Jest Config

*/
function jestConfig() {

  return {
    "testURL": "http://wpstest.test",
    "testEnvironment": "node",
    "verbose": true,
    "roots": [
      "<rootDir>/admin/js/app",
      "<rootDir>/public/js/app"
    ],
    "testPathIgnorePatterns": [
      "<rootDir>/node_modules",
      "<rootDir>/admin/js/app/vendor/",
      "<rootDir>/public/js/dist/",
      "<rootDir>/dist/",
      "<rootDir>/bin/",
      "<rootDir>/classes/",
      "<rootDir>/lib/",
      "<rootDir>/tests/",
      "<rootDir>/temapltes/",
      "<rootDir>/vendor/",
      "<rootDir>/webhooks/",
      "<rootDir>/gulp/"
    ],
    "setupFiles": [
      "jest-localstorage-mock"
    ]
  }

}


/*

Webpack Config

*/
function webpackConfig(outputFinalname) {

  return {
    watch: false,
    mode: config.isBuilding ? 'production' : 'development',
    cache: true,
    output: {
      filename: outputFinalname
    },
    resolve: {
      extensions: ['.js']
    },
    plugins: [
      new webpack.optimize.ModuleConcatenationPlugin(),
      new ProgressBarPlugin()
    ],
    optimization: {
      minimizer: [
        new UglifyJsPlugin({
          parallel: true,
          cache: true,
          parallel: true,
          extractComments: config.isBuilding ? true : false,
          uglifyOptions: {
            compress: config.isBuilding ? true : false,
            ecma: 6,
            mangle: config.isBuilding ? true : false,
            safari10: true
          },
          sourceMap: config.isBuilding ? false : true,
        })
      ]
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /(node_modules)/,
          enforce: 'pre',
          use: [
            'babel-loader?presets[]=es2015&plugins[]=transform-async-to-generator'
          ],
        }
      ]
    }
  }

}


/*

Postcss Config

*/
function postCSSPlugins() {

  var plugins = [
    willChangeTransition,
    willChange,
    autoprefixer({ browsers: ['last 6 version'] }),
    presetEnv(), // Allows usage of future CSS
    mqpacker(),
    colormin({
      legacy: true
    })
  ];

  // Only run if npm run gulp --build
  if (config.isBuilding) {
    plugins.push(cssnano({zindex: false}));
  }

  return plugins;

}


/*

Style Lint Config

*/
function stylelintConfig() {
  return {
    config: {
      rules: {
        "declaration-block-no-duplicate-properties": true,
        "block-no-empty": true,
        "no-extra-semicolons": true,
        "font-family-no-duplicate-names": true
      }
    },
    debug: true,
    reporters: [ { formatter: 'string', console: true }]
  }
}

config.postCSSPlugins = postCSSPlugins;
config.webpackConfig = webpackConfig;
config.stylelintConfig = stylelintConfig;
config.jestConfig = jestConfig;

export default config;
