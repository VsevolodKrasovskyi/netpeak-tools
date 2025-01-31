jQuery(document).ready(function($) {
    function checkLicenseStatusFromCDN() {
        const authToken = localStorage.getItem("authToken");
        const licenseKey = localStorage.getItem("licenseKey");

        if (!authToken || !licenseKey) {
            disableLicensedFeatures();
            return;
        }

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
                if (response.success && response.is_valid && response.is_activate) {
                    enableLicensedFeatures();
                } else {
                    disableLicensedFeatures();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
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
            $(this).attr('data-license-message', NetpeakData.license_message);
        });
    }

    checkLicenseStatusFromCDN();
});