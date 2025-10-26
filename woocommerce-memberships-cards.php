<?php
/**
 * Plugin Name: WooCommerce Memberships Cards
 * Plugin URI: https://github.com/yourusername/woocommerce-memberships-cards
 * Description: Display membership cards with PDF download functionality on My Account page
 * Version: 1.0.2
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

// Load composer autoloader
if (file_exists(WC_MEMBERSHIPS_CARDS_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once WC_MEMBERSHIPS_CARDS_PLUGIN_DIR . 'vendor/autoload.php';
}

// Load helper functions
require_once WC_MEMBERSHIPS_CARDS_PLUGIN_DIR . 'includes/functions.php';

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
        if (!function_exists('wc_memberships')) {
            add_action('admin_notices', 'wc_memberships_cards_memberships_missing_notice');
            return;
        }

        // Initialize main plugin class
        Plugin::get_instance();
    }

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

