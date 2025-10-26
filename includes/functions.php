<?php
/**
 * Helper functions
 *
 * @package WooCommerceMembershipsCards
 */

declare(strict_types=1);

use WooCommerceMembershipsCards\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get logo URL for a membership plan
 *
 * @param int $plan_id Plan ID.
 * @return string Logo URL or empty string.
 */
function wc_memberships_cards_get_plan_logo(int $plan_id): string {
    $saved_logos = get_option('wc_memberships_cards_logos', []);
    $attachment_id = $saved_logos[$plan_id] ?? 0;

    if (!$attachment_id) {
        return '';
    }

    $image_url = wp_get_attachment_image_url($attachment_id, 'full');

    return $image_url ?: '';
}

/**
 * Get profile fields for a membership
 *
 * @param \WC_Memberships_User_Membership $membership Membership object.
 * @return array Profile fields array.
 */
function wc_memberships_cards_get_profile_fields(\WC_Memberships_User_Membership $membership): array {
    $profile_fields = [];

    if (!method_exists($membership, 'get_profile_fields')) {
        return $profile_fields;
    }

    $fields = $membership->get_profile_fields();

    if (empty($fields)) {
        return $profile_fields;
    }

    foreach ($fields as $field) {
        $profile_fields[] = [
            'label' => $field->get_label(),
            'value' => $field->get_value(),
            'name' => $field->get_name(),
        ];
    }

    // Allow filtering
    return apply_filters('wc_memberships_cards_profile_fields', $profile_fields, $membership);
}

