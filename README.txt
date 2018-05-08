=== WP Shopify ===
Contributors: andrewmrobbins
Donate link: https://wpshop.io/purchase/
Tags: shopify, ecommerce, store, sell, products, shop, purchase, buy, wpshopify
Requires at least: 4.4
Requires PHP: 5.6
Tested up to: 4.9.5
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sell and build custom Shopify experiences on WordPress.

== Description ==

WP Shopify allows you to sell your Shopify products on any WordPress site. Your store data is synced as custom post types giving you the ability to utilize the full power of native WordPress functionality. On the front-end we use the [Shopify Buy Button](https://www.shopify.com/buy-button) to create an easy to use cart experience without the use of any iFrames.

= Features =
* Sync your products and collections as native WordPress post
* Templates
* No iFrames
* Over 100+ actions and filters allowing you to customize any part of the storefront
* Display your products using custom pages and shortcodes
* Built-in cart experience using [Shopify's Buy Button](https://www.shopify.com/buy-button)
* SEO optimized
* Advanced access to your Shopify data saved in custom database tables

See the [full list of features here](https://wpshop.io/how/)

https://www.youtube.com/watch?v=lYm6G35e8sI

= PRO =
WP Shopify is also available in a professional version which includes automatic syncing, templates, cross domain tracking, live support, and much more functionality! [Learn more](https://wpshop.io/)

= Links =
* [Website](https://wpshop.io/)
* [Documentation](https://wpshop.io/docs)
* [Live Support via Slack](https://join.slack.com/t/wpshopify/shared_invite/enQtMTg5OTQxODEwOTM1LTkxOTE5OWFiOTJmYTY3NTliMDUxMmNkN2Y2MzliZjdjMjA4ODA0YzU4ODIwZGRhNWIyMjkyYjg1MTc5YTc5MjM)
* [WP Shopify Pro](https://wpshop.io/)


== Installation ==
From your WordPress dashboard

1. Visit Plugins > Add New
2. Search for *WP Shopify*
3. Activate WP Shopify from your Plugins page
4. Create a [Shopify private app](https://wpshop.io/docs). More [info here](https://help.shopify.com/manual/apps/private-apps)
5. Back in WordPress, click on the menu item __WP Shopify__ and begin syncing your Shopify store to WordPress.
6. We've created a [guide](https://wpshop.io/docs) if you need help during the syncing process

== Screenshots ==
[https://wpshop.io/screenshots/1-syncing-cropped.jpg  Easy and fast syncing process]
[https://wpshop.io/screenshots/2-settings-cropped.jpg  Many settings and options to choose from]
[https://wpshop.io/screenshots/3-posts-cropped.jpg  Sync your store as native WordPress posts]


== Frequently Asked Questions ==

Read the [full list of FAQ](https://wpshop.io/faq/)

= How does this work? =
You can think of WordPress as the frontend and Shopify as the backend. You manage your store (add products, change prices, etc) from within Shopify and those changes sync into WordPress. WP Shopify also allows you to sell your products and is bundled with a cart experience using the [Shopify Buy Button SDK](https://www.shopify.com/buy-button).

After installing the plugin you connect your Shopify store to WordPress by filling in your Shopify API keys. After syncing, you can display / sell your products in various ways such as:

1. Using the default pages “yoursite.com/products” and “yoursite.com/collections“
2. Shortcodes [wps_products] and [wps_collections]

We also save your Shopify products as Custom Post Types enabling you to harness the native power of WordPress.

= Doesn’t Shopify already have a WordPress plugin? =
Technically yes but it [has been discontinued](https://wptavern.com/shopify-discontinues-its-official-plugin-for-wordpress).

Shopify has instead moved attention to their [Buy Button](https://www.shopify.ca/buy-button) which is an open-source library that allows you to embed products with snippets of HTML and JavaScript. The main drawback to this is that Shopify uses iFrames for the embeds which limit the ability for layout customizations.

WP Shopify instead uses a combination of the Buy Button and Shopify API to create an iFrame-free experience. This gives allows you to sync Shopify data directly into WordPress. We also save the products and collections as Custom Post Types which unlocks the native power of WordPress.

= Is this SEO friendly? =
We’ve gone to great lengths to ensure we’ve conformed to all the SEO best practices including semantic alt text, Structured Data, and indexable content.

= Does this work with third party Shopify apps? =
Unfortunately no. We rely on the main Shopify API which doesn’t expose third-party app data. However the functionality found in many of the Shopify apps can be reproduced by other WordPress plugins.

= How do I display my products? =
Documentation on how to display your products can be [found here](https://wpshop.io/docs/displaying).

= How does the checkout process work? =
WP Shopify does not handle any portion of the checkout process. When a customer clicks the checkout button within the cart, they’re redirected to the default Shopify checkout page to finish the process. The checkout page is opened in a new tab.

More information on the Shopify checkout process can be [found here](https://help.shopify.com/manual/sell-online/checkout-settings).

= Does this work with Shopify's Lite plan? =
Absolutely! In fact this is our recommendation if you intend to only sell on WordPress. More information on Shopify's [Lite plan](https://www.shopify.com/lite)


== Changelog ==
Our changelog can be [found here](https://wpshop.io/changelog/)
