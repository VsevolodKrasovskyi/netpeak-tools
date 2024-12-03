<script>
jQuery(document).ready(function ($) {

// Show the loading spinner
function showLoader() {
    $('#loader').show();
    $('#response').hide();
}

// Hide the loading spinner
function hideLoader() {
    $('#loader').hide();
    $('#response').show();
}

// Initialize license status check when the page loads
checkLicenseStatus();

// Check the license status from localStorage or the database
function checkLicenseStatus() {
    let authToken = localStorage.getItem("authToken");
    let licenseKey = localStorage.getItem("licenseKey");

    if (authToken && licenseKey) {
        // If tokens exist in localStorage, validate the license
        validateLicense(authToken, licenseKey);
    } else {
        // If tokens are missing, retrieve them from the database
        showLoader();
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

                        // Validate the license
                        validateLicense(authToken, licenseKey);
                    } else {
                        // Show the authentication form if tokens are not available
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

// Validate the license by making an API request
function validateLicense(authToken, licenseKey) {
    try {
        showLoader();
        $.ajax({
            url: 'https://cdn.netpeak.dev/api/check-license-status',
            type: 'POST',
            headers: { 'Authorization': 'Bearer ' + authToken },
            data: {
                license_key: licenseKey,
                domain: '<?php echo $_SERVER['HTTP_HOST']; ?>'
            },
            success: function(response) {
                hideLoader();
                if (response.success && response.is_valid && response.is_activate) {
                    // If the license is active and valid
                    $('#auth-form').hide();
                    $('#license-form').hide();
                    $('#response').html('<p class="status-cdn success-status"><?php _e("License is active and valid.", "netpeak-seo"); ?>' 
                    + (response.expires_date ? ' <?php _e("Expires on:", "netpeak-seo"); ?> ' + response.expires_date : ' <?php _e("Lifetime license.", "netpeak-seo"); ?>') + '</p>');
                }
            },
            error: function(xhr) {
                hideLoader();
                // Handle various error responses based on status code
                if (xhr.status === 401) {
                    $('#response').html('<p class="status-cdn error-status"><?php _e("Authorization required. Please log in again.", "netpeak-seo"); ?></p>');
                } else if (xhr.status === 403) {
                    $('#response').html('<p class="status-cdn error-status"><?php _e("License is invalid. Please contact support.", "netpeak-seo"); ?></p>');
                } else if (xhr.status === 404) {
                    $('#response').html('<p class="status-cdn error-status"><?php _e("License not found. Please check your license key.", "netpeak-seo"); ?></p>');
                } else if (xhr.status === 500) {
                    $('#response').html('<p class="status-cdn error-status"><?php _e("Server error. Please try again later.", "netpeak-seo"); ?></p>');
                } else {
                    $('#response').html('<p class="status-cdn error-status"><?php _e("An error occurred while checking the license status. Please try again later.", "netpeak-seo"); ?></p>');
                }
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
    let email = $('[name="email"]').val();
    let password = $('[name="password"]').val();

    $.ajax({
        url: 'https://cdn.netpeak.dev/api/login',
        type: 'POST',
        data: {
            email: email,
            password: password
        },
        success: function(response) {
            hideLoader();
            if (response.success) {
                let authToken = response.token;
                $('#auth-form').hide();
                $('#license-form').show();
                $('#response').html('<p class="status-cdn success-status"><?php _e("Authentication successful. Please enter your license key.", "netpeak-seo"); ?></p>');
                saveTokens(authToken, ''); // Save the authToken to localStorage and DB
                saveCredentials(email, password);
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

// Activate license handler
$('#license-submit').on('click', function(event) {
    event.preventDefault();

    let authToken = localStorage.getItem("authToken");
    if (!authToken) {
        $('#response').html('<p class="status-cdn error-status"><?php _e("Authentication token is missing. Please log in first.", "netpeak-seo"); ?></p>');
        return;
    }

    showLoader();

    $.ajax({
        url: 'https://cdn.netpeak.dev/api/activate-license',
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
                saveTokens(authToken, licenseKey); // Save tokens to both DB and localStorage
                checkLicenseStatus();
            } else {
                $('#response').html('<p class="status-cdn error-status">' + (response.message || '<?php _e("License verification failed: Invalid license key.", "netpeak-seo"); ?>') + '</p>');
            }
        },
        error: function(xhr) {
            hideLoader();
            // Handle different errors based on status code
            if (xhr.status === 401) {
                $('#response').html('<p class="status-cdn error-status"><?php _e("Authorization required. Please log in again.", "netpeak-seo"); ?></p>');
            } else if (xhr.status === 403) {
                $('#response').html('<p class="status-cdn error-status"><?php _e("License is invalid. Please contact support.", "netpeak-seo"); ?></p>');
            } else if (xhr.status === 404) {
                $('#response').html('<p class="status-cdn error-status"><?php _e("License not found. Please check your license key.", "netpeak-seo"); ?></p>');
            } else if (xhr.status === 500) {
                $('#response').html('<p class="status-cdn error-status"><?php _e("Server error. Please try again later.", "netpeak-seo"); ?></p>');
            } else {
                $('#response').html('<p class="status-cdn error-status"><?php _e("An error occurred while checking the license status. Please try again later.", "netpeak-seo"); ?></p>');
            }
            removeTokens();
        }
    });
});

// Save the tokens to localStorage and database
function saveTokens(authToken, licenseKey) {
    if (authToken) localStorage.setItem("authToken", authToken);
    if (licenseKey) localStorage.setItem("licenseKey", licenseKey);

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

// Remove the tokens from localStorage and database
function removeTokens() {
    localStorage.removeItem("authToken");
    localStorage.removeItem("licenseKey");
    saveTokens('', ''); // Deleting tokens from the database
}

// Save credentials (email and password) to localStorage and database
function saveCredentials(email, password) {
    if (email) localStorage.setItem("email", email);
    if (password) localStorage.setItem("password", password);

    $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            action: 'save_credentials',
            email: email,
            password: password
        },
        success: function(response) {
            if (!response.success) {
                $('#response').html('<p class="status-cdn error-status">Failed to save credentials.</p>');
            }
        },
        error: function() {
            $('#response').html('<p class="status-cdn error-status">Error saving credentials.</p>');
        }
    });
}

});

</script>