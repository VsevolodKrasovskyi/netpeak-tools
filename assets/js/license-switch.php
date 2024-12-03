<style>
.licensed-feature.disabled {
    pointer-events: none; 
    position: relative; 
    opacity: 0.8; 
}

.licensed-feature.disabled * {
    filter: blur(2px);
}


.licensed-feature.disabled::after {
    content: "<?php _e('This feature is available only with a license', 'netpeak-seo'); ?>";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 8px;
    border-radius: 5px;
    font-size: 14px;
    text-align: center;
    pointer-events: none; 
    z-index: 1; 
    filter: none; 
    opacity: 1;
}
</style>

<script>
jQuery(document).ready(function($) {
    function checkLicenseStatusFromCDN() {
        const authToken = localStorage.getItem("authToken");
        const licenseKey = localStorage.getItem("licenseKey");

        if (!authToken || !licenseKey) {
            console.error("No auth token or license key found in localStorage.");
            disableLicensedFeatures();
            return;
        }

        console.log("Sending request to CDN with authToken and licenseKey");

        $.ajax({
            url: 'https://cdn.netpeak.dev/api/check-license-status',
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + authToken
            },
            data: {
                license_key: licenseKey,
                domain: document.domain
            },
            success: function(response) {
                console.log("Received response from CDN:", response);
                if (response.success && response.is_valid && response.is_activate) {
                    enableLicensedFeatures();
                } else {
                    disableLicensedFeatures();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error checking license status from CDN:", textStatus, errorThrown);
                disableLicensedFeatures();
            }
        });
    }

    function enableLicensedFeatures() {
        $('.licensed-feature').each(function() {
            $(this).removeClass('disabled');
            $(this).find('.dependent-checkbox').prop('disabled', false);
        });
    }

    function disableLicensedFeatures() {
        $('.licensed-feature').each(function() {
            $(this).addClass('disabled');
            $(this).find('.dependent-checkbox').prop('disabled', true).prop('checked', false);
        });
    }

    // Проверяем лицензию при загрузке страницы
    checkLicenseStatusFromCDN();
});

</script>
