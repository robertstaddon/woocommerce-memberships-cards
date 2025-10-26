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
$status = $membership->get_status();
$end_date = $membership->get_end_date();

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
            width: 850px;
            height: 600px;
            position: relative;
            background: #f5f5f5;
        }
        
        .membership-card {
            width: 100%;
            height: 100%;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .card-header {
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-logo {
            max-width: 120px;
            max-height: 120px;
        }
        
        .card-logo img {
            max-width: 100%;
            height: auto;
        }
        
        .card-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            flex: 1;
            text-align: center;
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
            <?php if ($logo_url) : ?>
                <div class="card-logo">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_html($plan->get_name()); ?>" />
                </div>
            <?php endif; ?>
            
            <h1 class="card-title"><?php echo esc_html($plan->get_name()); ?></h1>
            
            <span class="card-status status-<?php echo esc_attr($status); ?>">
                <?php echo esc_html(ucfirst($status)); ?>
            </span>
        </div>

        <?php if ($end_date) : ?>
            <div class="card-field">
                <span class="field-label"><?php esc_html_e('Expires:', 'woocommerce-memberships-cards'); ?></span>
                <span class="field-value"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($end_date))); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($profile_fields)) : ?>
            <div class="profile-fields">
                <?php foreach ($profile_fields as $field) : ?>
                    <?php if (!empty($field['value'])) : ?>
                        <div class="card-field">
                            <span class="field-label"><?php echo esc_html($field['label']); ?>:</span>
                            <span class="field-value"><?php echo esc_html($field['value']); ?></span>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="footer">
            <p><?php echo esc_html(get_bloginfo('name')); ?> &copy; <?php echo esc_html(gmdate('Y')); ?></p>
        </div>
    </div>
</body>
</html>

