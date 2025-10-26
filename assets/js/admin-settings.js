/**
 * Admin settings JavaScript
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Handle logo upload
        $(document).on('click', '.wc-memberships-cards-upload-logo', function(e) {
            e.preventDefault();

            const $button = $(this);
            const planId = $button.data('plan-id');

            // Create the media uploader (always fresh to capture correct planId)
            const mediaUploader = wp.media({
                title: 'Select Logo Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            // When an image is selected, run a callback
            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();

                // Save via AJAX
                $.ajax({
                    url: wcMembershipsCards.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'wc_memberships_cards_save_logo',
                        nonce: wcMembershipsCards.nonce,
                        plan_id: planId,
                        attachment_id: attachment.id
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update preview
                            updateLogoPreview(planId, response.data.image_url, response.data.attachment_id);

                            // Update hidden field
                            $('input[data-plan-id="' + planId + '"]').val(response.data.attachment_id);

                            // Show remove button if hidden
                            $('[data-plan-id="' + planId + '"]').find('.wc-memberships-cards-remove-logo').remove();
                            $button.after(
                                '<button type="button" class="button wc-memberships-cards-remove-logo" data-plan-id="' + planId + '">Remove</button>'
                            );
                        }
                    }
                });
            });

            // Open the uploader
            mediaUploader.open();
        });

        // Handle logo removal
        $(document).on('click', '.wc-memberships-cards-remove-logo', function(e) {
            e.preventDefault();

            const $button = $(this);
            const planId = $button.data('plan-id');

            // Save empty via AJAX
            $.ajax({
                url: wcMembershipsCards.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'wc_memberships_cards_save_logo',
                    nonce: wcMembershipsCards.nonce,
                    plan_id: planId,
                    attachment_id: 0
                },
                success: function(response) {
                    if (response.success) {
                        // Clear preview
                        updateLogoPreview(planId, '', '');

                        // Update hidden field
                        $('input[data-plan-id="' + planId + '"]').val('');

                        // Remove remove button
                        $button.remove();
                    }
                }
            });
        });

        /**
         * Update logo preview
         */
        function updateLogoPreview(planId, imageUrl, attachmentId) {
            const $preview = $('[data-plan-id="' + planId + '"]').find('.wc-memberships-cards-logo-preview');
            const $hiddenField = $('[data-plan-id="' + planId + '"]').find('input.logo-attachment-id');

            if (imageUrl) {
                $preview.html('<img src="' + imageUrl + '" alt="" style="max-width: 150px; height: auto;" />');
            } else {
                $preview.html('<span class="no-logo">No logo set</span>');
            }
        }
    });
})(jQuery);

