<?php
/**
 * Membership card PDF template
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
$status        = $membership->get_status();
$end_date      = $membership->get_end_date();
$next_payment  = '';

if (method_exists($membership, 'get_next_bill_on_local_date') && function_exists('wc_date_format')) {
    $next_payment = (string) $membership->get_next_bill_on_local_date(wc_date_format());
}

// Get customer information
$user_id    = $membership->get_user_id();
$user       = get_userdata($user_id);
$first_name = get_user_meta($user_id, 'first_name', true);
$last_name  = get_user_meta($user_id, 'last_name', true);
$email      = $user ? $user->user_email : '';

// Use absolute URL for logo in PDF
$logo_url = '';
if ($plan_logo) {
    // Convert to absolute URL if relative
    if (!filter_var($plan_logo, FILTER_VALIDATE_URL)) {
        $logo_url = site_url($plan_logo);
    } else {
        $logo_url = $plan_logo;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: #f5f5f5;
        }
        
        .membership-card {
            width: 90%;
            background: #ffffff;
            border-radius: 12px;
            padding: 40px 50px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            page-break-inside: avoid;
        }
        
        .card-header {
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .card-header-left {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        
        .card-header-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        
        .card-logo {
            max-width: 150px;
            max-height: 80px;
            margin-bottom: 10px;
        }
        
        .card-logo img {
            max-width: 100%;
            height: auto;
        }
        
        .card-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        
        .card-status {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .card-status.status-active {
            background: #4caf50;
            color: white;
        }
        
        .card-status.status-expired {
            background: #f44336;
            color: white;
        }
        
        .card-status.status-cancelled {
            background: #ff9800;
            color: white;
        }
        
        .card-field {
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .field-label {
            font-weight: bold;
            color: #666;
            margin-right: 10px;
        }
        
        .field-value {
            color: #333;
        }
        
        .profile-fields {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .legal-notice {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #444;
        }

        .legal-notice h2 {
            font-size: 14px;
            margin: 0 0 10px 0;
            font-weight: bold;
        }

        .legal-notice ol {
            margin: 0 0 0 18px;
            padding: 0;
        }

        .legal-notice li {
            margin-bottom: 6px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="membership-card">
        <div class="card-header">
            <div class="card-header-left">
                <?php if ($logo_url) : ?>
                    <div class="card-logo">
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_html($plan->get_name()); ?>" />
                    </div>
                <?php endif; ?>

                <h1 class="card-title"><?php echo esc_html($plan->get_name()); ?></h1>

                <?php if ($end_date) : ?>
                    <div class="card-field" style="margin-top:8px; font-size:13px;">
                        <span class="field-label"><?php esc_html_e('Expiration:', 'woocommerce-memberships-cards'); ?></span>
                        <span class="field-value">
                            <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($end_date))); ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($next_payment)) : ?>
                    <div class="card-field" style="margin-top:4px; font-size:13px;">
                        <span class="field-label"><?php esc_html_e('Active until:', 'woocommerce-memberships-cards'); ?></span>
                        <span class="field-value">
                            <?php echo esc_html($next_payment); ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($first_name || $last_name) : ?>
            <div class="card-field">
                <span class="field-label"><?php esc_html_e('Name:', 'woocommerce-memberships-cards'); ?></span>
                <span class="field-value"><?php echo esc_html(trim($first_name . ' ' . $last_name)); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($email) : ?>
            <div class="card-field">
                <span class="field-label"><?php esc_html_e('Email:', 'woocommerce-memberships-cards'); ?></span>
                <span class="field-value"><?php echo esc_html($email); ?></span>
            </div>
        <?php endif; ?>

        <div class="card-field">
            <span class="field-label"><?php esc_html_e('Member ID:', 'woocommerce-memberships-cards'); ?></span>
            <span class="field-value"><?php echo esc_html((string) $user_id); ?></span>
        </div>

        <?php if (!empty($profile_fields)) : ?>
            <?php foreach ($profile_fields as $field) : ?>
                <?php if (!empty($field['value'])) : ?>
                    <div class="card-field">
                        <span class="field-label"><?php echo esc_html($field['label']); ?>:</span>
                        <span class="field-value"><?php echo esc_html($field['value']); ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="legal-notice">
            <h2>If you are pulled over</h2>
            <ol>
                <li>Do not engage the officer. If he asks "Do you know why I pulled you over?" say "I am not sure. I always follow the law."</li>
                <li>Calmy take the ticket and follow the officers instructions -- we will get our day in court to argue your case.</li>
            </ol>
            <p style="margin-top:10px;">For assistance, call 888-253-6235 or email tickets@atdsa.com to report any citations.</p>
        </div>

        <div class="footer">
            <p><?php echo esc_html(get_bloginfo('name')); ?> &copy; <?php echo esc_html(gmdate('Y')); ?></p>
        </div>
    </div>
</body>
</html>

