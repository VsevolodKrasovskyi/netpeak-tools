<script>
jQuery(document).ready(function($) {

    function showLoader() {
        $('#loader').show();
        $('#response').hide();
    }

    function hideLoader() {
        $('#loader').hide();
        $('#response').show();
    }

    // Checking the license status
    checkLicenseStatus();

    function checkLicenseStatus() {
        let authToken = localStorage.getItem("authToken");
        let licenseKey = localStorage.getItem("licenseKey");

        if (authToken && licenseKey) {
            validateLicense(authToken, licenseKey);
        } else {
            showLoader();

            // If the tokens are missing in localStorage, we get them from the database
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: { action: 'get_license_tokens' },
                success: function(response) {
                    hideLoader();
                    if (response.success) {
                        authToken = response.data.authToken;
                        licenseKey = response.data.licenseKey;

                        if (authToken && licenseKey) {
                            // Save tokens to localStorage
                            localStorage.setItem("authToken", authToken);
                            localStorage.setItem("licenseKey", licenseKey);

                            validateLicense(authToken, licenseKey);
                        } else {
                            $('#auth-form').show();
                            $('#license-form').hide();
                        }
                    } else {
                        $('#response').html('<p class="status-cdn error-status">Failed to retrieve tokens.</p>');
                    }
                },
                error: function() {
                    hideLoader();
                    $('#response').html('<p class="status-cdn error-status">Error retrieving tokens.</p>');
                }
            });
        }
    }

    function validateLicense(authToken, licenseKey) {
        try {
            showLoader();
            $.ajax({
                url: 'https://cdn.netpeak.dev/api/check-license-status',
                type: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + authToken
                },
                data: {
                    license_key: licenseKey,
                    domain: '<?php echo $_SERVER['HTTP_HOST']; ?>'
                },
                success: function(response) {
                    hideLoader();
                    if (response.success && response.is_valid && response.is_activate) {
                        $('#auth-form').hide();
                        $('#license-form').hide();
                        $('#response').html('<p class="status-cdn success-status"><?php _e("License is active and valid.", "netpeak-seo"); ?>' 
                        + (response.expires_date ? ' <?php _e("Expires on:", "netpeak-seo"); ?> ' + response.expires_date : '') + '</p>');
                    } else {
                        $('#auth-form').show();
                        $('#license-form').hide();
                        $('#response').html('<p class="status-cdn error-status"><?php _e("License is inactive or invalid.", "netpeak-seo"); ?></p>');
                        removeTokens();
                    }
                },
                error: function() {
                    hideLoader();
                    $('#response').html('<p class="status-cdn error-status">Error checking license status.</p>');
                    removeTokens();
                }
            });
        } catch (error) {
            hideLoader();
            console.error("Unexpected error:", error);
            $('#response').html('<p class="status-cdn error-status"><?php _e("An unexpected error occurred.", "netpeak-seo"); ?></p>');
            removeTokens();
        }
    }

    // Authentication handler
    $('#auth-submit').on('click', function(event) {
        event.preventDefault();
        showLoader();

        $.ajax({
            url: 'https://cdn.netpeak.dev/api/login',
            type: 'POST',
            data: {
                email: $('[name="email"]').val(),
                password: $('[name="password"]').val()
            },
            success: function(response) {
                hideLoader();
                if (response.success) {
                    let authToken = response.token;
                    $('#auth-form').hide();
                    $('#license-form').show();
                    $('#response').html('<p class="status-cdn success-status"><?php _e("Authentication successful. Please enter your license key.", "netpeak-seo"); ?></p>');
                    saveTokens(authToken, ''); // Save the authToken to the db and localStorage
                } else {
                    $('#response').html('<p class="status-cdn error-status">' + response.message + '</p>');
                }
            },
            error: function() {
                hideLoader();
                $('#response').html('<p class="status-cdn error-status">Error during authentication.</p>');
            }
        });
    });

    // Licence verification handler
    $('#license-submit').on('click', function(event) {
        event.preventDefault();

        let authToken = localStorage.getItem("authToken");
        if (!authToken) {
            $('#response').html('<p class="status-cdn error-status"><?php _e("Authentication token is missing. Please log in first.", "netpeak-seo"); ?></p>');
            return;
        }

        showLoader();

        $.ajax({
            url: 'https://cdn.netpeak.dev/api/check-license',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + authToken
            },
            data: {
                domain: '<?php echo $_SERVER['HTTP_HOST']; ?>',
                key: $('[name="license-key"]').val()
            },
            success: function(response) {
                hideLoader();
                if (response.success) {
                    let licenseKey = $('[name="license-key"]').val();
                    $('#response').html('<p class="status-cdn success-status"><?php _e("License verification successful: License is valid and activated.", "netpeak-seo"); ?></p>');
                    $('#license-form').hide();
                    saveTokens(authToken, licenseKey); // Save tokens to both the database and localStorage
                    checkLicenseStatus();
                } else {
                    $('#response').html('<p class="status-cdn error-status">' + (response.message || '<?php _e("License verification failed: Invalid license key.", "netpeak-seo"); ?>') + '</p>');
                }
            },
            error: function() {
                hideLoader();
                $('#response').html('<p class="status-cdn error-status">Error verifying license.</p>');
            }
        });
    });

    function saveTokens(authToken, licenseKey) {
        // Save tokens to localStorage
        if (authToken) localStorage.setItem("authToken", authToken);
        if (licenseKey) localStorage.setItem("licenseKey", licenseKey);

        // Save tokens in the database
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'save_license_tokens',
                authToken: authToken,
                licenseKey: licenseKey
            },
            success: function(response) {
                if (!response.success) {
                    $('#response').html('<p class="status-cdn error-status">Failed to save tokens.</p>');
                }
            },
            error: function() {
                $('#response').html('<p class="status-cdn error-status">Error saving tokens.</p>');
            }
        });
    }

    function removeTokens() {
        localStorage.removeItem("authToken");
        localStorage.removeItem("licenseKey");

        saveTokens('', ''); // Deleting tokens from the database
    }

});
</script>
