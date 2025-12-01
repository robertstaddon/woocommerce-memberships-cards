<?php
/**
 * Membership card template
 *
 * @package WooCommerceMembershipsCards
 * @var \WC_Memberships_User_Membership $membership Membership object.
 * @var \WC_Memberships_Membership_Plan $plan Membership plan.
 * @var string $plan_logo Plan logo URL.
 * @var array  $profile_fields Profile fields.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// Get status info
$status = $membership->get_status();
$end_date = $membership->get_end_date();

// Get customer information
$user_id = $membership->get_user_id();
$user = get_userdata($user_id);
$first_name = get_user_meta($user_id, 'first_name', true);
$last_name = get_user_meta($user_id, 'last_name', true);
$email = $user ? $user->user_email : '';

// Generate PDF URL with cache-busting query arg
$pdf_url = add_query_arg(
    [
        'membership_card_pdf' => $membership->get_id(),
        'nonce'               => wp_create_nonce('membership_card_pdf_' . $membership->get_id()),
        't'                   => (string) wp_rand(100000, 999999),
    ],
    home_url()
);
?>

<div class="wc-membership-card">
    <div class="wc-membership-card-header">
        <div class="wc-membership-card-header-top">
            <div class="wc-membership-card-header-left">
                <?php if ($plan_logo) : ?>
                    <div class="wc-membership-card-logo">
                        <img src="<?php echo esc_url($plan_logo); ?>" alt="<?php echo esc_attr($plan->get_name()); ?>" />
                    </div>
                <?php endif; ?>
            </div>

            <div class="wc-membership-card-header-right">
                <span class="wc-membership-card-status status-<?php echo esc_attr($status); ?>">
                    <?php echo esc_html(ucfirst($status)); ?>
                </span>
            </div>
        </div>

        <h3 class="wc-membership-card-title"><?php echo esc_html($plan->get_name()); ?></h3>
        <?php if ($end_date) : ?>
        <div class="wc-membership-card-field">
            <span class="wc-membership-card-field-label"><?php esc_html_e('Expires:', 'woocommerce-memberships-cards'); ?></span><span class="wc-membership-card-field-value"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($end_date))); ?></span>
        </div>
        <?php endif; ?>
    </div>

    <div class="wc-membership-card-customer-info">
        <?php if ($first_name || $last_name) : ?>
            <div class="wc-membership-card-field">
                <span class="wc-membership-card-field-label"><?php esc_html_e('Name:', 'woocommerce-memberships-cards'); ?></span><span class="wc-membership-card-field-value"><?php echo esc_html(trim($first_name . ' ' . $last_name)); ?></span>
            </div>
        <?php endif; ?>
        <?php if ($email) : ?>
            <div class="wc-membership-card-field">
                <span class="wc-membership-card-field-label"><?php esc_html_e('Email:', 'woocommerce-memberships-cards'); ?></span><span class="wc-membership-card-field-value"><?php echo esc_html($email); ?></span>
            </div>
        <?php endif; ?>
        <?php if (!empty($profile_fields)) : ?>
            <?php foreach ($profile_fields as $field) : ?>
                <?php if (!empty($field['value'])) : ?>
                    <div class="wc-membership-card-field">
                        <span class="wc-membership-card-field-label"><?php echo esc_html($field['label']); ?>:</span><span class="wc-membership-card-field-value"><?php echo esc_html($field['value']); ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

    <div class="wc-membership-card-footer">
        <a href="<?php echo esc_url($pdf_url); ?>" class="wc-membership-card-download button" target="_blank">
            <?php esc_html_e('Download', 'woocommerce-memberships-cards'); ?>
        </a>
    </div>
</div>

