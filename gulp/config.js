////////////
// Config //
////////////

import browserSync from 'browser-sync';

const config = {

  files: {
    php: ["./*/**.php"],
    jsPublic: [
      './public/js/app/**/*.js',
      '!./public/js/app.min.js',
      '!./public/js/vendor.min.js',
      '!./public/js/app.min.js.map'
    ],
    jsAdmin: [
      './admin/js/app/**/*.js',
      '!./admin/js/app.min.js',
      '!./admin/js/vendor.min.js',
      '!./admin/js/app.min.js.map'
    ],
    jsEntryPublic: './public/js/app/app.js',
    jsEntryAdmin: './admin/js/app/app.js',
    cssPublic: './public/css/**/*.scss',
    cssEntryPublic: './public/css/app/app.scss',
    cssAdmin: './admin/css/**/*.scss',
    cssEntryAdmin: './admin/css/app/app.scss'
  },
  folders: {
    cssPublic: './public/css/dist',
    jsPublic: './public/js/dist',
    cssAdmin: './admin/css/dist',
    jsAdmin: './admin/js/dist'
  },
  names: {
    jsVendorPublic: 'public.vendor.min.js',
    jsVendorAdmin: 'admin.vendor.min.js',
    jsPublic: 'public.min.js',
    cssPublic: 'public.min.css',
    jsAdmin: 'admin.min.js',
    cssAdmin: 'admin.min.css'
  },
  bs: browserSync.create()

};

export default config;
