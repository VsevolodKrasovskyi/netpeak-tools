<script>
jQuery(document).ready(function($) {
    const dependentCheckboxes = $('.dependent-checkbox')
    function checkLicenseStatusFromCDN() {
        const authToken = localStorage.getItem("authToken");
        const licenseKey = localStorage.getItem("licenseKey");

        if (!authToken || !licenseKey) {
            console.error("No auth token or license key found in localStorage.");
            disableDependentCheckboxes();
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
                    enableDependentCheckboxes();
                } else {
                    disableDependentCheckboxes();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error checking license status from CDN:", textStatus, errorThrown);
                disableDependentCheckboxes();
            }
        });
    }

    function enableDependentCheckboxes() {
        dependentCheckboxes.each(function() {
            $(this).prop('disabled', false);
            $(this).parent().removeClass('switch-disable');
            console.log("Enabling checkbox", this);
        });
    }

    function disableDependentCheckboxes() {
        dependentCheckboxes.each(function() {
            $(this).prop('disabled', true);
            $(this).prop('checked', false);
            $(this).parent().addClass('switch-disable');
            console.log("Disabling checkbox", this);
        });
    }

    checkLicenseStatusFromCDN();
});
</script>