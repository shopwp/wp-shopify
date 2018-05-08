<p align="center">
  <a href="https://wpshop.io">
    <img src="https://cdn.rawgit.com/arobbins/wp-shopify/master/public/imgs/logo-new-wpshop-horz.svg" width="30%" height="auto">
  </a>
</p>

<p align="center" font="10px">Sell and build custom Shopify experiences on WordPress. </p>

<p align="center">
  <a href="https://wpshop.io" target="_blank">Website</a> |
  <a href="https://wpshop.io/docs" target="_blank">Documentation</a> |
  <a href="https://wpshop.io/docs/installation" target="_blank">Installation</a> |
  <a href="https://wpshop.io/docs/syncing" target="_blank">Syncing</a>
</p>

<div align="center">
  <a href="https://travis-ci.com/arobbins/wp-shopify-pro" style="text-align: center;">
    <img src="https://api.travis-ci.com/arobbins/wp-shopify-pro.svg?token=FmC2p6cxqRrxLpZfViYm&branch=master" alt="WP Shopify Travis CI Build Status" style="text-align: center;display:inline-block;margin: 0 auto;">
  </a>
</div><br>

WP Shopify allows you to sell your Shopify products on any WordPress site. Your store data is synced as custom post types giving you the ability to utilize the full power of native WordPress functionality. On the front-end we use the [Shopify Buy Button](https://www.shopify.com/buy-button) to create an easy to use cart experience without the use of any iFrames.

## Installation
From your WordPress dashboard

1. Visit Plugins -> Add New
2. Search for __WP Shopify__
3. Activate WP Shopify from your Plugins page
4. Create a [Shopify Private App](https://wpshop.io/docs). More [info here](https://help.shopify.com/manual/apps/private-apps)
5. Back in WordPress, click on the menu item __WP Shopify__ and begin syncing your Shopify store to WordPress.
6. We've created a [guide](https://wpshop.io/docs) if you need help during the syncing process

## Features
* Sync your products and collections as native WordPress posts
* No iFrames
* Templates
* Over 100+ actions and filters allowing you to customize any part of the storefront
* Display your products using custom pages and shortcodes
* Built-in cart experience using [Shopify's Buy Button](https://www.shopify.com/buy-button)
* SEO optimized
* Advanced access to your Shopify data saved in custom database tables

See the [full list of features here](https://wpshop.io/how/)

### WP Shopify Pro
WP Shopify is also available in a professional version which includes automatic syncing, templates, cross domain tracking, live support, and much more functionality! [Learn more](https://wpshop.io/)

## Screenshots
![Easy and fast Shopify syncing process](https://wpshop.io/screenshots/1-syncing-cropped.jpg)
![Sync your store as native WordPress posts](https://wpshop.io/screenshots/3-posts-cropped.jpg)
![Many settings and options to choose from](https://wpshop.io/screenshots/2-settings-cropped.jpg)

## FAQ

Read the [full list of FAQ](https://wpshop.io/faq/)

__How does this work?__

You can think of WordPress as the frontend and Shopify as the backend. You manage your store (add products, change prices, etc) from within Shopify and those changes sync into WordPress. WP Shopify also allows you to sell your products and is bundled with a cart experience using the [Shopify Buy Button SDK](https://www.shopify.com/buy-button).

After installing the plugin you connect your Shopify store to WordPress by filling in your Shopify API keys. After syncing, you can display / sell your products in various ways such as:

1. Using the default pages “yoursite.com/products” and “yoursite.com/collections“
2. Shortcodes [wps_products] and [wps_collections]

We also save your Shopify products as Custom Post Types enabling you to harness the native power of WordPress.

__Doesn’t Shopify already have a WordPress plugin?__

Technically yes but it [has been discontinued](https://wptavern.com/shopify-discontinues-its-official-plugin-for-wordpress).

Shopify has instead moved attention to their [Buy Button](https://www.shopify.ca/buy-button) which is an open-source library that allows you to embed products with snippets of HTML and JavaScript. The main drawback to this is that Shopify uses iFrames for the embeds which limit the ability for layout customizations.

WP Shopify instead uses a combination of the Buy Button and Shopify API to create an iFrame-free experience. This gives allows you to sync Shopify data directly into WordPress. We also save the products and collections as Custom Post Types which unlocks the native power of WordPress.

__Is this SEO friendly?__

We’ve gone to great lengths to ensure we’ve conformed to all the SEO best practices including semantic alt text, Structured Data, and indexable content.

__Does this work with third party Shopify apps?__

Unfortunately no. We rely on the main Shopify API which doesn’t expose third-party app data. However the functionality found in many of the Shopify apps can be reproduced by other WordPress plugins.

__How do I display my products?__

Documentation on how to display your products can be [found here](https://wpshop.io/docs/displaying).

__How does the checkout process work?__

WP Shopify does not handle any portion of the checkout process. When a customer clicks the checkout button within the cart, they’re redirected to the default Shopify checkout page to finish the process. The checkout page is opened in a new tab.

More information on the Shopify checkout process can be [found here](https://help.shopify.com/manual/sell-online/checkout-settings).

__Does this work with Shopify's Lite plan?__

Absolutely! In fact this is our recommendation if you intend to only sell on WordPress. More information on Shopify's [Lite plan](https://www.shopify.com/lite)

## Changelog
Our changelog can be [found here](https://wpshop.io/changelog/)
