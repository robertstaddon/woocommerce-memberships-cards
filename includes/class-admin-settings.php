<?php
/**
 * Admin settings page
 *
 * @package WooCommerceMembershipsCards
 */

declare(strict_types=1);

namespace WooCommerceMembershipsCards;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin settings class
 */
class Admin_Settings {
    /**
     * Option name
     */
    private const OPTION_NAME = 'wc_memberships_cards_logos';

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_wc_memberships_cards_save_logo', [$this, 'ajax_save_logo']);
    }

    /**
     * Add settings page to WooCommerce menu
     */
    public function add_settings_page(): void {
        add_submenu_page(
            'woocommerce',
            esc_html__('Membership Cards Logos', 'woocommerce-memberships-cards'),
            esc_html__('Membership Cards', 'woocommerce-memberships-cards'),
            'manage_woocommerce',
            'wc-memberships-cards-logos',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Register settings
     */
    public function register_settings(): void {
        register_setting(
            'wc_memberships_cards_logos',
            self::OPTION_NAME,
            [
                'type' => 'array',
                'sanitize_callback' => [$this, 'sanitize_logos'],
            ]
        );
    }

    /**
     * Render settings page
     */
    public function render_settings_page(): void {
        if (!current_user_can('manage_woocommerce')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'woocommerce-memberships-cards'));
        }

        $plans = wc_memberships_get_membership_plans();
        $saved_logos = get_option(self::OPTION_NAME, []);

        include WC_MEMBERSHIPS_CARDS_PLUGIN_DIR . 'admin/settings-page.php';
    }

    /**
     * Enqueue admin scripts and styles
     *
     * @param string $hook Current admin page hook.
     */
    public function enqueue_scripts(string $hook): void {
        if ('woocommerce_page_wc-memberships-cards-logos' !== $hook) {
            return;
        }

        wp_enqueue_media();
        wp_enqueue_script(
            'wc-memberships-cards-admin',
            WC_MEMBERSHIPS_CARDS_PLUGIN_URL . 'assets/js/admin-settings.js',
            ['jquery'],
            WC_MEMBERSHIPS_CARDS_VERSION,
            true
        );

        wp_localize_script(
            'wc-memberships-cards-admin',
            'wcMembershipsCards',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wc_memberships_cards_ajax'),
            ]
        );

        wp_enqueue_style(
            'wc-memberships-cards-admin',
            WC_MEMBERSHIPS_CARDS_PLUGIN_URL . 'assets/css/admin-settings.css',
            [],
            WC_MEMBERSHIPS_CARDS_VERSION
        );
    }

    /**
     * AJAX handler to save logo
     */
    public function ajax_save_logo(): void {
        check_ajax_referer('wc_memberships_cards_ajax', 'nonce');

        if (!current_user_can('manage_woocommerce')) {
            wp_send_json_error(['message' => esc_html__('Insufficient permissions', 'woocommerce-memberships-cards')]);
        }

        $plan_id = absint($_POST['plan_id'] ?? 0);
        $attachment_id = absint($_POST['attachment_id'] ?? 0);

        if (!$plan_id) {
            wp_send_json_error(['message' => esc_html__('Invalid plan ID', 'woocommerce-memberships-cards')]);
        }

        $saved_logos = get_option(self::OPTION_NAME, []);
        $saved_logos[$plan_id] = $attachment_id;

        update_option(self::OPTION_NAME, $saved_logos);

        $image_url = '';
        if ($attachment_id) {
            $image_url = wp_get_attachment_image_url($attachment_id, 'full');
        }

        wp_send_json_success([
            'attachment_id' => $attachment_id,
            'image_url' => $image_url,
        ]);
    }

    /**
     * Sanitize logos option
     *
     * @param array $logos Logos array.
     * @return array Sanitized logos.
     */
    public function sanitize_logos(array $logos): array {
        $sanitized = [];

        foreach ($logos as $plan_id => $attachment_id) {
            $sanitized[absint($plan_id)] = absint($attachment_id);
        }

        return $sanitized;
    }
}

