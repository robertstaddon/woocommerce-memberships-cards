<?php
/**
 * Admin settings page template
 *
 * @package WooCommerceMembershipsCards
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// phpcs:ignore WordPress.WP.GlobalVariablesOverride
$saved_logos = get_option('wc_memberships_cards_logos', []);
?>

<div class="wrap woocommerce-memberships-cards-settings">
    <h1><?php esc_html_e('Membership Cards Logos', 'woocommerce-memberships-cards'); ?></h1>

    <p><?php esc_html_e('Assign logo images to each membership plan. These logos will appear on the membership cards.', 'woocommerce-memberships-cards'); ?></p>

    <form method="post" action="options.php">
        <?php settings_fields('wc_memberships_cards_logos'); ?>

        <table class="form-table wc-memberships-cards-plans-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Membership Plan', 'woocommerce-memberships-cards'); ?></th>
                    <th><?php esc_html_e('Logo', 'woocommerce-memberships-cards'); ?></th>
                    <th><?php esc_html_e('Actions', 'woocommerce-memberships-cards'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plans as $plan) : ?>
                    <?php
                    $plan_id = $plan->get_id();
                    $attachment_id = $saved_logos[$plan_id] ?? 0;
                    $image_url = '';
                    if ($attachment_id) {
                        $image_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
                    }
                    ?>
                    <tr data-plan-id="<?php echo esc_attr($plan_id); ?>">
                        <td>
                            <strong><?php echo esc_html($plan->get_name()); ?></strong>
                        </td>
                        <td>
                            <div class="wc-memberships-cards-logo-preview">
                                <?php if ($image_url) : ?>
                                    <img src="<?php echo esc_url($image_url); ?>" alt="" style="max-width: 150px; height: auto;" />
                                <?php else : ?>
                                    <span class="no-logo"><?php esc_html_e('No logo set', 'woocommerce-memberships-cards'); ?></span>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="<?php echo esc_attr(\WooCommerceMembershipsCards\Admin_Settings::OPTION_NAME); ?>[<?php echo esc_attr($plan_id); ?>]" value="<?php echo esc_attr($attachment_id); ?>" class="logo-attachment-id" />
                        </td>
                        <td>
                            <button type="button" class="button wc-memberships-cards-upload-logo" data-plan-id="<?php echo esc_attr($plan_id); ?>">
                                <?php esc_html_e('Select Logo', 'woocommerce-memberships-cards'); ?>
                            </button>
                            <?php if ($image_url) : ?>
                                <button type="button" class="button wc-memberships-cards-remove-logo" data-plan-id="<?php echo esc_attr($plan_id); ?>">
                                    <?php esc_html_e('Remove', 'woocommerce-memberships-cards'); ?>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php submit_button(); ?>
    </form>
</div>

