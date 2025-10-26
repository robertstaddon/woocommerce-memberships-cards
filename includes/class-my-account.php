<?php
/**
 * My Account endpoint for membership cards
 *
 * @package WooCommerceMembershipsCards
 */

declare(strict_types=1);

namespace WooCommerceMembershipsCards;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * My Account endpoint class
 */
class My_Account {
    /**
     * Constructor
     */
    public function __construct() {
        // Register endpoint
        add_filter('woocommerce_account_menu_items', [$this, 'add_menu_item']);
        add_action('woocommerce_account_membership-cards_endpoint', [$this, 'display_cards']);
        add_action('init', [$this, 'add_rewrite_rule']);

        // Enqueue styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);

        // Handle PDF generation
        add_action('template_redirect', [$this, 'handle_pdf_download']);
    }

    /**
     * Add menu item to My Account
     *
     * @param array $items Menu items.
     * @return array
     */
    public function add_menu_item(array $items): array {
        // Remove logout and insert before it
        $logout = $items['customer-logout'];
        unset($items['customer-logout']);

        $items['membership-cards'] = esc_html__('Membership Cards', 'woocommerce-memberships-cards');
        $items['customer-logout'] = $logout;

        return $items;
    }

    /**
     * Add rewrite rule for endpoint
     */
    public function add_rewrite_rule(): void {
        add_rewrite_endpoint('membership-cards', EP_ROOT | EP_PAGES);
    }

    /**
     * Display membership cards
     */
    public function display_cards(): void {
        if (!is_user_logged_in()) {
            return;
        }

        $user_id = get_current_user_id();
        $memberships = wc_memberships_get_user_memberships($user_id);

        // No memberships message
        if (empty($memberships)) {
            echo '<div class="woocommerce-memberships-cards-empty">';
            echo '<p>' . esc_html__('You don\'t have any membership cards yet.', 'woocommerce-memberships-cards') . '</p>';
            echo '</div>';
            return;
        }

        // Display cards
        echo '<div class="woocommerce-memberships-cards-container">';
        foreach ($memberships as $membership) {
            $this->display_single_card($membership);
        }
        echo '</div>';
    }

    /**
     * Display single membership card
     *
     * @param \WC_Memberships_User_Membership $membership Membership object.
     */
    private function display_single_card(\WC_Memberships_User_Membership $membership): void {
        $plan = $membership->get_plan();
        $plan_logo = wc_memberships_cards_get_plan_logo($plan->get_id());
        $profile_fields = wc_memberships_cards_get_profile_fields($membership);

        Plugin::load_template(
            'membership-card.php',
            [
                'membership' => $membership,
                'plan' => $plan,
                'plan_logo' => $plan_logo,
                'profile_fields' => $profile_fields,
            ]
        );
    }

    /**
     * Enqueue frontend styles
     */
    public function enqueue_styles(): void {
        if (!is_account_page()) {
            return;
        }

        wp_enqueue_style(
            'woocommerce-memberships-cards',
            WC_MEMBERSHIPS_CARDS_PLUGIN_URL . 'assets/css/membership-cards.css',
            [],
            WC_MEMBERSHIPS_CARDS_VERSION
        );
    }

    /**
     * Handle PDF download request
     */
    public function handle_pdf_download(): void {
        if (!isset($_GET['membership_card_pdf'])) { // phpcs:ignore WordPress.Security.NonceVerification
            return;
        }

        $membership_id = absint($_GET['membership_card_pdf']); // phpcs:ignore WordPress.Security.NonceVerification
        $nonce = sanitize_text_field($_GET['nonce'] ?? ''); // phpcs:ignore WordPress.Security.NonceVerification

        // Verify nonce
        $nonce_action = 'membership_card_pdf_' . $membership_id;
        if (!wp_verify_nonce($nonce, $nonce_action)) {
            wp_die(esc_html__('Invalid request', 'woocommerce-memberships-cards'), '', ['response' => 403]);
        }

        // Check if user is logged in and owns this membership
        if (!is_user_logged_in()) {
            wp_die(esc_html__('You must be logged in', 'woocommerce-memberships-cards'), '', ['response' => 403]);
        }

        $membership = wc_memberships_get_user_membership($membership_id);
        $user_id = get_current_user_id();

        if (!$membership || $membership->get_user_id() !== $user_id) {
            wp_die(esc_html__('Membership not found', 'woocommerce-memberships-cards'), '', ['response' => 404]);
        }

        // Generate PDF
        $pdf_generator = new PDF_Generator();
        $pdf_generator->generate($membership);
        exit;
    }
}

