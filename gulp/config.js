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
import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import OptimizeCSSAssetsPlugin from 'optimize-css-assets-webpack-plugin';
import OptimizeCSSClassnamesPlugin from 'optimize-css-classnames-plugin';
import BundleAnalyzerPlugin from 'webpack-bundle-analyzer';


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
    // Manually setting files for now to improve performance
    toBeProcessed: [
      './_tmp/admin/js/**/*',
      './_tmp/admin/partials/**/*',
      './_tmp/public/js/**/*',
      './_tmp/public/templates/**/*',
      './_tmp/classes/**/*',
      './_tmp/wp-shopify.php',
      './_tmp/uninstall.php'
    ],
    pluginTitleSettings: [
      './_tmp/admin/partials/wps-admin-display.php'
    ],
    pluginUpdateFunction: [
      './_tmp/classes/class-hooks.php'
    ],
    tmp: './_tmp/**/*',
    tmpAll: [
      './_tmp/**/*',
      './_tmp/**/.*'
    ],
    test: 'hello/*',
    all: [
      './**/*',
      '!./yarn.lock',
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
      '!./package.json',
      '!./phpunit.xml.dist',
      '!./postcss.config.js',
      '!./gulpfile.babel.js'
    ],
    // Represents all the files needed for other developers to work with. What gets commited to the free repo.
    onlyWorking: [
      './**/*',
      './**/.*',
      './**/.**/**',
      '!./node_modules/**',
      '!./.git/**',
      '!./_tmp/**',
      '!./_free/**',
      '!./.gitignore'
    ],
    build: './**/*',
    buildProContent: '../../../../assets/wp-shopify-pro/**/*',
    buildFreeContent: '../../../../assets/wpshopify/**/*',
    // Files / folders that DONT exist in the free version
    buildFreeClear: [
      './_tmp/webhooks',
      './_tmp/admin/partials/settings/settings-webhooks-urls.php',
      './_tmp/admin/partials/settings/settings-selective-sync.php',
      './_tmp/.git/**',
      './_tmp/.gitignore'
    ],
    buildSuperfluousClear: [
      './_tmp/admin/js/app',
      './_tmp/public/js/app',
      './_tmp/admin/css/app',
      './_tmp/public/css/app',
      './_tmp/dist/public.min.js.LICENSE'
    ],
    buildZip: argvs.argv.tier === 'free' ? '/Users/arobbins/www/wpstest/assets/wpshopify/wpshopify.zip' : '/Users/arobbins/www/wpstest/assets/wp-shopify-pro/wp-shopify-pro.zip',
    buildRoot: argvs.argv.tier === 'free' ? '/Users/arobbins/www/wpstest/assets/wpshopify' : '/Users/arobbins/www/wpstest/assets/wp-shopify-pro',
    buildEntry: [
      './admin/js/app/tools/tools.js',
      './admin/partials/wps-tab-content-tools.php'
    ],
    js: [
      './public/js/app/**/*.js',
      './public/js/app/**/*.jsx',
      '!./public/js/app.min.js',
      '!./public/js/vendor.min.js',
      '!./public/js/app.min.js.map',
      './admin/js/app/**/*.js',
      './admin/js/app/**/*.jsx',
      '!./admin/js/app.min.js',
      '!./admin/js/vendor.min.js',
      '!./admin/js/app.min.js.map'
    ],
    jsPublic: [ // doesnt need tmp check
      './public/js/app/**/*.js',
      './public/js/app/**/*.jsx',
      '!./public/js/app.min.js',
      '!./public/js/vendor.min.js',
      '!./public/js/app.min.js.map'
    ],
    jsAdmin: [ // doesnt need tmp check
      './admin/js/app/**/*.js',
      './admin/js/app/**/*.jsx',
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
    freeRepo: './_free',
    plugin: './',
    dist: argvs.argv.tier ? './_tmp/dist' : './dist',
    pro: '../../../../assets/wp-shopify-pro',
    free: '../../../../assets/wpshopify',
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
  isBuilding: argvs.argv.tier ? true : false,
  buildTier: argvs.argv.tier ? argvs.argv.tier : false, // Build type can be either 'free' or 'pro'
  buildRelease: argvs.argv.release ? argvs.argv.release : false, // Plugin version number
  currentRelease: argvs.argv.current ? argvs.argv.current : false // Plugin version number
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
      "<rootDir>/admin/js/vendor/",
      "<rootDir>/public/js/dist/",
      "<rootDir>/public/js/vendor/",
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

  var webpackConfigObj = {
    watch: false,
    // mode: config.isBuilding ? 'production' : 'development',
    mode: 'production',
    cache: true,

    // IMPORTANT: This entry will override an entry set within webpack stream
    entry: {
      public: config.isBuilding ? './_tmp/public/js/app/app' : './public/js/app/app',
      admin: config.isBuilding ? './_tmp/admin/js/app/app' : './admin/js/app/app'
    },
    output: {
      filename: '[name].min.js',
      chunkFilename: '[name].min.js',
    },
    resolve: {
      extensions: ['.js']
    },
    plugins: [
      new webpack.optimize.ModuleConcatenationPlugin(),
      new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
      new ProgressBarPlugin(),
      new MiniCssExtractPlugin({
        filename: "gutenberg-components.min.css",
        chunkFilename: "gutenberg-components.min.css"
      })
    ],
    optimization: {
      splitChunks: {
        chunks: "all",
        minSize: 0,
        automaticNameDelimiter: '-'
      },
      occurrenceOrder: true,
      minimizer: [
        new UglifyJsPlugin({
          parallel: true,
          cache: true,
          extractComments: false,
          uglifyOptions: {
            compress: true,
            ecma: 6,
            mangle: {
              keep_fnames: false
            },
            safari10: true,
            ie8: false,
            warnings: false
          },
          sourceMap: false,
        }),
        new OptimizeCSSAssetsPlugin({})
      ]
    },
    module: {
      rules: [
        {
          test: /\.css$/,
          use: [
            MiniCssExtractPlugin.loader,
            "css-loader"
          ]
        },
        {
          test: /\.(js|jsx)$/i,
          exclude: /node_modules/,
          enforce: 'pre',
          use: [
            {
              loader: 'babel-loader',
              options: {
                babelrcRoots:  [".", "./_tmp/*"],
                presets: [
                  '@babel/preset-env',
                  '@babel/preset-react'
                ]
              }
            }
          ]
        }
      ]
    }
  }

  if (config.isBuilding) {

    webpackConfigObj.plugins.push(
      new webpack.DefinePlugin({
        'process.env.NODE_ENV': JSON.stringify('production')
      })
    );

  }

  return webpackConfigObj;

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
