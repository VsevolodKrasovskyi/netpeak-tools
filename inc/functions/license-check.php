<script>
jQuery(document).ready(function($) {
    // Show the loader when the page loads
    function showLoader() {
        $('#loader').show();
        $('#response').hide();
    }

    function hideLoader() {
        $('#loader').hide();
        $('#response').show();
    }

    // Checking the licence status 
    checkLicenseStatus();

    function checkLicenseStatus() {
        let authToken = localStorage.getItem("authToken");
        let licenseKey = localStorage.getItem("licenseKey");

        if (!authToken || !licenseKey) {
            $('#auth-form').show();
            $('#license-form').hide();
            return;
        }

        showLoader();

        try {
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
                        localStorage.removeItem("authToken");
                        localStorage.removeItem("licenseKey");
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    let errorMessage = "Error checking license status.";
                    if (xhr.responseText) {
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            errorMessage = errorResponse.message || errorMessage;
                        } catch (e) {
                            console.error("Error parsing response JSON:", e);
                        }
                    }
                    $('#response').html('<p class="status-cdn error-status">' + errorMessage + '</p>');
                    localStorage.removeItem("authToken");
                    localStorage.removeItem("licenseKey");
                }
            });
        } catch (error) {
            hideLoader();
            console.error("Unexpected error:", error);
            $('#response').html('<p class="status-cdn error-status"><?php _e("An unexpected error occurred.", "netpeak-seo"); ?></p>');
            localStorage.removeItem("authToken");
            localStorage.removeItem("licenseKey");
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
                    localStorage.setItem("authToken", response.token);
                    $('#auth-form').hide();
                    $('#license-form').show();
                    $('#response').html('<p class="status-cdn success-status"><?php _e("Authentication successful. Please enter your license key.", "netpeak-seo"); ?></p>');
                } else {
                    $('#response').html('<p class="status-cdn error-status">' + response.message + '</p>');
                }
            },
            error: function(xhr) {
                hideLoader();
                let errorMessage = "Error during authentication.";
                if (xhr.responseText) {
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        errorMessage = errorResponse.message || errorMessage;
                    } catch (e) {
                        console.error("Error parsing response JSON:", e);
                    }
                }
                $('#response').html('<p class="status-cdn error-status">' + errorMessage + '</p>');
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
                    localStorage.setItem("licenseKey", $('[name="license-key"]').val());
                    $('#response').html('<p class="status-cdn success-status"><?php _e("License verification successful: License is valid and activated.", "netpeak-seo"); ?></p>');
                    $('#license-form').hide();
                    checkLicenseStatus();
                } else {
                    $('#response').html('<p class="status-cdn error-status">' + (response.message || '<?php _e("License verification failed: Invalid license key.", "netpeak-seo"); ?>') + '</p>');
                }
            },
            error: function(xhr) {
                hideLoader();
                let errorMessage = "Error verifying license.";
                if (xhr.responseText) {
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        errorMessage = errorResponse.message || errorMessage;
                    } catch (e) {
                        console.error("Error parsing response JSON:", e);
                    }
                }
                $('#response').html('<p class="status-cdn error-status">' + errorMessage + '</p>');
            }
        });
    });
});
</script>
