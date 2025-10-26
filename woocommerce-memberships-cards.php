<?php
/**
 * Plugin Name: WooCommerce Memberships Cards
 * Plugin URI: https://github.com/yourusername/woocommerce-memberships-cards
 * Description: Display membership cards with PDF download functionality on My Account page
 * Version: 1.0.10
 * Author: Your Name
 * Author URI: https://your-site.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woocommerce-memberships-cards
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 */

declare(strict_types=1);

use WooCommerceMembershipsCards\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
if (!defined('WC_MEMBERSHIPS_CARDS_VERSION')) {
    define('WC_MEMBERSHIPS_CARDS_VERSION', '1.0.0');
}

if (!defined('WC_MEMBERSHIPS_CARDS_PLUGIN_DIR')) {
    define('WC_MEMBERSHIPS_CARDS_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('WC_MEMBERSHIPS_CARDS_PLUGIN_URL')) {
    define('WC_MEMBERSHIPS_CARDS_PLUGIN_URL', plugin_dir_url(__FILE__));
}

if (!defined('WC_MEMBERSHIPS_CARDS_PLUGIN_BASENAME')) {
    define('WC_MEMBERSHIPS_CARDS_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

// Load composer autoloader (for vendor dependencies like Dompdf)
if (file_exists(WC_MEMBERSHIPS_CARDS_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once WC_MEMBERSHIPS_CARDS_PLUGIN_DIR . 'vendor/autoload.php';
}

// Manually load plugin classes (hybrid approach)
require_once WC_MEMBERSHIPS_CARDS_PLUGIN_DIR . 'includes/class-plugin.php';
require_once WC_MEMBERSHIPS_CARDS_PLUGIN_DIR . 'includes/class-my-account.php';
require_once WC_MEMBERSHIPS_CARDS_PLUGIN_DIR . 'includes/class-admin-settings.php';
require_once WC_MEMBERSHIPS_CARDS_PLUGIN_DIR . 'includes/class-pdf-generator.php';

// Load helper functions
require_once WC_MEMBERSHIPS_CARDS_PLUGIN_DIR . 'includes/functions.php';

/**
 * Check if WooCommerce Memberships is active
 *
 * @return bool
 */
function wc_memberships_cards_is_memberships_active(): bool {
    $active_plugins = (array) get_option('active_plugins', []);
    
    if (is_multisite()) {
        $active_plugins = array_merge($active_plugins, array_keys(get_site_option('active_sitewide_plugins', [])));
    }
    
    $plugin_file = 'woocommerce-memberships/woocommerce-memberships.php';
    $is_plugin_active = in_array($plugin_file, $active_plugins, true) || array_key_exists($plugin_file, $active_plugins);
    
    return get_option('wc_memberships_is_active', false) && $is_plugin_active;
}

// Initialize plugin
if (!function_exists('wc_memberships_cards_init')) {
    /**
     * Initialize the plugin
     */
    function wc_memberships_cards_init(): void {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', 'wc_memberships_cards_woocommerce_missing_notice');
            return;
        }

        // Check if WooCommerce Memberships is active
        if (!wc_memberships_cards_is_memberships_active()) {
            add_action('admin_notices', 'wc_memberships_cards_memberships_missing_notice');
            return;
        }

        // Check if Plugin class is loaded (composer dependencies installed)
        if (!class_exists('\WooCommerceMembershipsCards\Plugin')) {
            add_action('admin_notices', 'wc_memberships_cards_dependencies_missing_notice');
            return;
        }

        // Initialize main plugin class
        \WooCommerceMembershipsCards\Plugin::get_instance();
    }

    // Check dependencies at admin_init (for admin context)
    add_action('admin_init', 'wc_memberships_cards_init');
    
    // Also initialize at plugins_loaded (for frontend)
    add_action('plugins_loaded', 'wc_memberships_cards_init');

    // Declare WooCommerce HPOS compatibility
    add_action('before_woocommerce_init', function() {
        if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
                'custom_order_tables',
                __FILE__,
                true
            );
        }
    });
}

/**
 * Display notice if WooCommerce is not active
 */
function wc_memberships_cards_woocommerce_missing_notice(): void {
    $message = sprintf(
        /* translators: %1$s is the plugin name, %2$s is WooCommerce */
        esc_html__('%1$s requires %2$s to be installed and active.', 'woocommerce-memberships-cards'),
        '<strong>' . esc_html__('WooCommerce Memberships Cards', 'woocommerce-memberships-cards') . '</strong>',
        '<strong>' . esc_html__('WooCommerce', 'woocommerce-memberships-cards') . '</strong>'
    );
    echo '<div class="error"><p>' . wp_kses_post($message) . '</p></div>';
}

/**
 * Display notice if WooCommerce Memberships is not active
 */
function wc_memberships_cards_memberships_missing_notice(): void {
    $message = sprintf(
        /* translators: %1$s is the plugin name, %2$s is WooCommerce Memberships */
        esc_html__('%1$s requires %2$s to be installed and active.', 'woocommerce-memberships-cards'),
        '<strong>' . esc_html__('WooCommerce Memberships Cards', 'woocommerce-memberships-cards') . '</strong>',
        '<strong>' . esc_html__('WooCommerce Memberships', 'woocommerce-memberships-cards') . '</strong>'
    );
    echo '<div class="error"><p>' . wp_kses_post($message) . '</p></div>';
}

/**
 * Display notice if composer dependencies are missing
 */
function wc_memberships_cards_dependencies_missing_notice(): void {
    $message = sprintf(
        /* translators: %s is the plugin name */
        esc_html__('%s requires composer dependencies to be installed. Please run "composer install" in the plugin directory.', 'woocommerce-memberships-cards'),
        '<strong>' . esc_html__('WooCommerce Memberships Cards', 'woocommerce-memberships-cards') . '</strong>'
    );
    echo '<div class="error"><p>' . wp_kses_post($message) . '</p></div>';
}

