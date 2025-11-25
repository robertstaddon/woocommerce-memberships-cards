# WooCommerce Memberships Cards

Display beautifully designed membership cards with PDF download functionality for WooCommerce Memberships.

## Description

WooCommerce Memberships Cards extends WooCommerce Memberships by adding a custom "Membership Cards" endpoint to the My Account page. Customers can view all their memberships as styled cards and download them as professional PDF documents.

## Features

- 🎴 **Beautiful Card Display**: View all memberships in a modern, card-based layout
- 📄 **PDF Download**: Download each membership card as a standalone PDF
- 🏢 **Custom Logos**: Assign custom logos to each membership plan from the admin
- 🎨 **Template System**: Easily customize card designs by overriding templates
- ✅ **Profile Fields**: Automatically displays all WooCommerce Memberships profile fields
- 📱 **Responsive Design**: Looks great on all devices
- 🔒 **Secure**: Proper permission checks and nonce verification

## Installation

### Via Git

1. Clone this repository into your WordPress plugins directory:
```bash
cd wp-content/plugins
git clone https://github.com/yourusername/woocommerce-memberships-cards.git
```

2. Install dependencies:
```bash
cd woocommerce-memberships-cards
composer install
```

3. Activate the plugin in WordPress admin

### Via Composer

```bash
composer create-project woocommerce/woocommerce-memberships-cards
```

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- WooCommerce (latest stable version)
- WooCommerce Memberships (latest stable version)

## Usage

### For Customers

Once activated, customers will see a new "Membership Cards" menu item in their My Account page. Clicking it displays all their memberships as cards, each showing:

- Membership plan name
- Status (Active, Expired, Cancelled, etc.)
- Expiration date
- All profile fields
- A download button for a PDF version

### For Administrators

**Setting Logos:**

1. Go to WooCommerce → Membership Cards
2. For each membership plan, click "Select Logo"
3. Choose an image from your media library
4. Save the settings

The logo will appear on all membership cards for that plan.

### For Developers

**Template Overrides:**

You can override any template by placing it in your theme:

```
your-theme/
  └── woocommerce-memberships-cards/
      ├── membership-card.php
      └── membership-card-pdf.php
```

**Available Hooks:**

```php
// Filter profile fields before display
add_filter('wc_memberships_cards_profile_fields', function($fields, $membership) {
    // Modify or filter profile fields
    return $fields;
}, 10, 2);

// Customize PDF options
add_filter('wc_memberships_cards_pdf_options', function($options) {
    // Modify Dompdf options
    return $options;
});
```

**Helper Functions:**

```php
// Get logo URL for a plan
$logo_url = wc_memberships_cards_get_plan_logo($plan_id);

// Get profile fields for a membership
$profile_fields = wc_memberships_cards_get_profile_fields($membership);

// Load template manually
Plugin::load_template('membership-card.php', $args);
```

## Changelog

### 1.0.12
- Reorganized membership card layout: logo at top, title underneath, status on the right
- Fixed BR tag appearing between field labels and values by removing newlines between spans
- Updated CSS to support new header layout structure

### 1.0.11
- Removed immediate AJAX save functionality, now saves only on "Save Changes" button click
- Simplified JavaScript to only update UI elements and hidden form fields
- Added `data-plan-id` attribute to hidden inputs for proper field selection
- Removed unused AJAX handler code from Admin_Settings class
- Form submission now properly saves all logos to database

### 1.0.10
- Fixed "Save Changes" button not persisting logos on Membership Cards Logos admin page
- Changed OPTION_NAME constant from private to public to allow template access
- Logos now properly save to database and persist after page reload

### 1.0.9
- Fixed logo selector bug where clicking "Select Logo" on any membership plan after the first incorrectly updated the first plan's logo
- Media uploader now creates fresh instance for each button click to capture correct planId
- Each membership plan now correctly updates its own logo when selected

### 1.0.8
- Fixed SSH key setup in GitHub Actions using here-document syntax
- Resolved multi-line private key formatting issues in deployment workflow
- Ensured proper handling of SSH private key content with newlines preserved

### 1.0.7
- Switched from SFTP to rsync for faster deployments
- Implemented delta sync to only transfer changed files
- Added SSH key-based authentication for secure deployment
- Deployment speed improved by 5-10x for subsequent pushes

### 1.0.6
- Optimized SFTP deployment to only upload changed files
- Added sync filters to exclude unnecessary files from deployment
- Significantly faster deployments by skipping unchanged files

### 1.0.5
- Implemented hybrid autoloading approach (vendor autoloader + manual class loading)
- Changed from PSR-4 to classmap autoloading for better WordPress compatibility
- Fixed class loading issues in deployed environments
- Improved compatibility with WordPress plugin architecture

### 1.0.4
- Fixed fatal error when Plugin class not found
- Added proper namespace reference for Plugin class
- Added check for composer dependencies installation
- Improved error messages for missing dependencies

### 1.0.3
- Implemented robust WooCommerce Memberships detection using plugin activation state and option checks
- Added support for multisite installations
- Fixed timing issues with plugin dependency checks

### 1.0.2
- Fixed WooCommerce Memberships detection using `wc_memberships()` function
- Added WooCommerce HPOS (High-Performance Order Storage) compatibility declaration
- Improved plugin activation reliability

### 1.0.0
- Initial release
- My Account endpoint for membership cards
- PDF download functionality
- Admin settings for membership plan logos
- Template override system
- Profile fields display

## Screenshots

[Screenshots would go here]

## Support

For support, please [open an issue on GitHub](https://github.com/yourusername/woocommerce-memberships-cards/issues).

## License

GPL v2 or later

License URI: https://www.gnu.org/licenses/gpl-2.0.html

## Credits

Developed for the WooCommerce ecosystem.

