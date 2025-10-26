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

// Generate PDF URL
$pdf_url = add_query_arg(
    [
        'membership_card_pdf' => $membership->get_id(),
        'nonce' => wp_create_nonce('membership_card_pdf_' . $membership->get_id()),
    ],
    home_url()
);
?>

<div class="wc-membership-card">
    <div class="wc-membership-card-header">
        <?php if ($plan_logo) : ?>
            <div class="wc-membership-card-logo">
                <img src="<?php echo esc_url($plan_logo); ?>" alt="<?php echo esc_attr($plan->get_name()); ?>" />
            </div>
        <?php endif; ?>

        <h3 class="wc-membership-card-title"><?php echo esc_html($plan->get_name()); ?></h3>

        <span class="wc-membership-card-status status-<?php echo esc_attr($status); ?>">
            <?php echo esc_html(ucfirst($status)); ?>
        </span>
    </div>

    <?php if ($end_date) : ?>
        <div class="wc-membership-card-field">
            <span class="wc-membership-card-field-label"><?php esc_html_e('Expires:', 'woocommerce-memberships-cards'); ?></span>
            <span class="wc-membership-card-field-value"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($end_date))); ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($profile_fields)) : ?>
        <div class="wc-membership-card-profile-fields">
            <?php foreach ($profile_fields as $field) : ?>
                <?php if (!empty($field['value'])) : ?>
                    <div class="wc-membership-card-field">
                        <span class="wc-membership-card-field-label"><?php echo esc_html($field['label']); ?>:</span>
                        <span class="wc-membership-card-field-value"><?php echo esc_html($field['value']); ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="wc-membership-card-footer">
        <a href="<?php echo esc_url($pdf_url); ?>" class="wc-membership-card-download button" target="_blank">
            <?php esc_html_e('Download PDF', 'woocommerce-memberships-cards'); ?>
        </a>
    </div>
</div>

