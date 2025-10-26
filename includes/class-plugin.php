<?php
/**
 * Main plugin class
 *
 * @package WooCommerceMembershipsCards
 */

declare(strict_types=1);

namespace WooCommerceMembershipsCards;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class
 */
class Plugin {
    /**
     * Singleton instance
     *
     * @var Plugin|null
     */
    private static ?Plugin $instance = null;

    /**
     * Get singleton instance
     *
     * @return Plugin
     */
    public static function get_instance(): Plugin {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init();
    }

    /**
     * Initialize the plugin
     */
    private function init(): void {
        // Load plugin text domain for translations
        add_action('init', [$this, 'load_textdomain']);

        // Initialize components
        if (is_admin()) {
            new Admin_Settings();
        }

        new My_Account();

        // Register activation hook
        register_activation_hook(WC_MEMBERSHIPS_CARDS_PLUGIN_BASENAME, [$this, 'activate']);

        // Register deactivation hook
        register_deactivation_hook(WC_MEMBERSHIPS_CARDS_PLUGIN_BASENAME, [$this, 'deactivate']);
    }

    /**
     * Load plugin text domain
     */
    public function load_textdomain(): void {
        load_plugin_textdomain(
            'woocommerce-memberships-cards',
            false,
            dirname(WC_MEMBERSHIPS_CARDS_PLUGIN_BASENAME) . '/languages'
        );
    }

    /**
     * Plugin activation
     */
    public function activate(): void {
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public function deactivate(): void {
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Get template path with override support
     *
     * @param string $template_name Template filename.
     * @return string Template path
     */
    public static function get_template_path(string $template_name): string {
        $template_path = locate_template('woocommerce-memberships-cards/' . $template_name);

        if (!$template_path) {
            $template_path = WC_MEMBERSHIPS_CARDS_PLUGIN_DIR . 'templates/' . $template_name;
        }

        // Allow filtering of template path
        return apply_filters('wc_memberships_cards_template_path', $template_path, $template_name);
    }

    /**
     * Load template with variables
     *
     * @param string $template_name Template filename.
     * @param array  $args Template variables.
     */
    public static function load_template(string $template_name, array $args = []): void {
        $template_path = self::get_template_path($template_name);

        if (file_exists($template_path)) {
            extract($args); // phpcs:ignore WordPress.PHP.DontExtract
            include $template_path;
        }
    }
}

