<?php
/**
 * PDF generator class
 *
 * @package WooCommerceMembershipsCards
 */

declare(strict_types=1);

namespace WooCommerceMembershipsCards;

use Dompdf\Dompdf;
use Dompdf\Options;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * PDF generator class
 */
class PDF_Generator {
    /**
     * Generate and output PDF for membership card
     *
     * @param \WC_Memberships_User_Membership $membership Membership object.
     */
    public function generate(\WC_Memberships_User_Membership $membership): void {
        $plan = $membership->get_plan();
        $plan_logo = wc_memberships_cards_get_plan_logo($plan->get_id());
        $profile_fields = wc_memberships_cards_get_profile_fields($membership);

        // Get template HTML
        ob_start();
        Plugin::load_template(
            'membership-card-pdf.php',
            [
                'membership' => $membership,
                'plan' => $plan,
                'plan_logo' => $plan_logo,
                'profile_fields' => $profile_fields,
            ]
        );
        $html = ob_get_clean();

        // Configure Dompdf options
        $options = apply_filters('wc_memberships_cards_pdf_options', new Options());
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        // Create Dompdf instance
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        // Use US Letter size (8.5 x 11 inches) in portrait orientation
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();

        // Output PDF
        $filename = sprintf(
            'membership-card-%d.pdf',
            $membership->get_id()
        );

        // Send appropriate headers
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        echo $dompdf->output(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}

