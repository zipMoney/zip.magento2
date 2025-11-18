define([
    'jquery'
], function ($) {
    'use strict';

    return function (config) {
        // Set global config for Zip widgets
        window.zipPaymentConfig = config;

        // Check if we should hide widgets based on config
        var checkProductAvailability = function () {
            if (!config.isActive) {
                return;
            }

            // For product pages - server already checked and sent us the result
            if (config.hasOwnProperty('isCurrentProductAllowed')) {
                if (config.isCurrentProductAllowed === false) {
                    hideZipWidgets();
                }
                return;
            }

            // For other pages - validator handles this on server side
            // No need to check on frontend
        };

        var hideZipWidgets = function () {
            // Hide widgets and banners with various selectors
            $(document).ready(function () {
                var selectors = [
                    '.zipmoney-widget',
                    '.zipmoney-banner',
                    '.zip-widget',
                    '.zip-banner',
                    '[class*="zip"][class*="widget"]',
                    '[class*="zip"][class*="banner"]',
                    '[id*="zip"][id*="widget"]',
                    '[id*="zip"][id*="banner"]'
                ];

                $(selectors.join(', ')).hide().remove();

                // Trigger event for custom implementations
                $(document).trigger('zipPaymentNotAvailable', {
                    reason: 'excluded_category',
                    productId: config.currentProductId,
                    config: config
                });
            });
        };

        // Run check when DOM is ready
        $(document).ready(function () {
            checkProductAvailability();
        });

        // Note: For configurable products, if variant changes require re-checking,
        // you would need AJAX call to server to check new product ID
        // This is intentionally simple - one check per page load
    };
});

