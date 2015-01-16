=== WP API Customizer ===
Contributors: ixkaito
Tags: api, json, custom field, rest api, rest
Requires: JSON REST API (WP API)
Requires at least: 4.0
Tested up to: 4.1
Version: 0.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make post meta data (custom field values) available for JSON REST API (WP API) when unauthenticated.

== Description ==

Due to security/privacy concerns, JSON REST API (WP API) only allows post meta data (custom field value) for authenticated users.
This plugin is an easy way to make post meta available when unauthenticated.

After activate the plugin, go to WP API Customizer option page.
Click the plus icon to add rows.
Set JSON attributes and custom field names in your posts that you want to use when unauthenticated.
The JSON REST API will provide the data as "post_meta" like the following:

`"post_meta":{"your_attribute_a":"Custom Field A value","your_attribute_b":"Custom Field B value"}`

== Installation ==

1. Upload the `wp-api-customizer` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the Plugins menu in WordPress.
3. Go to WP API Customizer option page.
4. Set JSON attributes and custom field names.

== Changelog ==

= 0.0.2 =
* Fix a bug that WP API Customizer options are deleted unexpectedly
* Solve conflicts with some plugins
* Delete the data in the database when uninstalling WP API Customizer

= 0.0.1 =
* Initial Release.
