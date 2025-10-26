=== WooCommerce Memberships Cards ===
Contributors: yourusername
Tags: woocommerce, memberships, cards, pdf, my-account
Requires at least: 6.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display beautifully designed membership cards with PDF download functionality for WooCommerce Memberships.

== Description ==

WooCommerce Memberships Cards extends WooCommerce Memberships by adding a custom "Membership Cards" endpoint to the My Account page. Customers can view all their memberships as styled cards and download them as professional PDF documents.

= Features =

* Beautiful card display for all memberships
* PDF download capability for each membership card
* Custom logo assignment per membership plan
* Template override system for easy customization
* Automatic display of all WooCommerce Memberships profile fields
* Responsive design for all devices
* Secure with proper permission checks

== Installation ==

= Automatic Installation =

1. Go to your WordPress admin panel
2. Navigate to Plugins → Add New
3. Search for "WooCommerce Memberships Cards"
4. Click "Install Now" and then "Activate"

= Manual Installation =

1. Download the plugin from the repository
2. Extract the files to your `wp-content/plugins/` directory
3. Run `composer install` in the plugin directory
4. Activate the plugin in WordPress admin

= Requirements =

* WordPress 6.0 or higher
* WooCommerce (latest stable version)
* WooCommerce Memberships (latest stable version)
* PHP 7.4 or higher

== Frequently Asked Questions ==

= Does this work with WooCommerce Memberships? =

Yes, this plugin requires WooCommerce Memberships to be installed and active.

= Can customers download their membership cards as PDF? =

Yes, each membership card has a download button that generates a PDF on-the-fly.

= Can I customize the card design? =

Yes, you can override templates in your theme directory. See the documentation for details.

= How do I assign logos to membership plans? =

Go to WooCommerce → Membership Cards in the admin, and use the media uploader to assign logos to each plan.

== Screenshots ==

1. Membership cards displayed on My Account page
2. Admin settings page for logo management
3. Downloaded PDF example

== Changelog ==

= 1.0.3 =
* Implemented robust WooCommerce Memberships detection using plugin activation state and option checks
* Added support for multisite installations
* Fixed timing issues with plugin dependency checks

= 1.0.2 =
* Fixed WooCommerce Memberships detection using wc_memberships() function
* Added WooCommerce HPOS compatibility declaration
* Improved plugin activation reliability

= 1.0.0 =
* Initial release
* My Account endpoint for membership cards
* PDF download functionality with Dompdf
* Admin settings for membership plan logos
* Template override system
* Automatic profile fields display
* Responsive card layout
* Security with nonce verification

== Upgrade Notice ==

= 1.0.3 =
Critical update - fixes WooCommerce Memberships detection issues in all environments.

= 1.0.2 =
Update recommended - fixes activation issues and adds WooCommerce compatibility declarations.

= 1.0.0 =
Initial release of WooCommerce Memberships Cards.

== Development ==

This plugin is open source and contributions are welcome!

== Support ==

For support, please open an issue on GitHub.

