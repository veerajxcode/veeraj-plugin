=== Veeraj Plugin ===
Contributors: veeraj
Tags: API, data display, caching, Gutenberg block, WP-CLI
Requires at least: 5.8
Tested up to: 6.7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: 
License URI: 

== Description ==
Veeraj Plugin fetches and displays data from a remote API. It provides a user-friendly admin interface, AJAX endpoints for fetching data, and WP-CLI commands for developers. Built with modern WordPress standards.

== Installation ==

Upload the veeraj-plugin folder to the /wp-content/plugins/ directory.

Activate the plugin through the "Plugins" menu in WordPress.

(Optional) Install dependencies for development:

composer install
npm install
npm run build

== Frequently Asked Questions ==

= What does this plugin do? =
The Veeraj Plugin retrieves data from a remote API, caches it, and displays it on a custom admin page and optionally in a Gutenberg block.

= How do I refresh the data? =
Use the "Refresh Data" button on the admin page or the WP-CLI command:

wp veeraj refresh-data

== Changelog ==

= 1.0.0 =

Initial release.

Fetch data from remote API with caching.

AJAX endpoints for fetching data.

Admin page with data display and refresh button.

WP-CLI command for clearing and refreshing the cache.

Localization-ready.

== Upgrade Notice ==

= 1.0.0 =
Initial release of the Veeraj Plugin.

== Screenshots ==

Admin page with the "Refresh Data" button and data display.

== License ==
This plugin is licensed under the MIT License. See the LICENSE file included in the plugin package.