document.addEventListener('DOMContentLoaded', initLicenseHandler);

function initLicenseHandler() {
    checkLicenseStatus();
}

//elements
const loaderElement = document.getElementById('loader') || null;
const responseElement = document.getElementById('netpeak-license-response') || null;
const authForm = document.getElementById('auth-form') || null;
const licenseForm = document.getElementById('license-form') || null;
const authSubmitButton = document.getElementById('auth-submit') || null;
const licenseSubmitButton = document.getElementById('license-submit') || null;

function showLoader() {
    if (loaderElement && responseElement) {
        loaderElement.style.display = 'block';
        responseElement.style.display = 'none';
    }
}

function hideLoader() {
    if (loaderElement && responseElement) {
        loaderElement.style.display = 'none';
        responseElement.style.display = 'block';
    }
}
//Cookies
function getCookie(name) {
    let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? match[2] : null;
}

function setCookie(name, value, days = 7) {
    let expires = new Date();
    expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = `${name}=${value};expires=${expires.toUTCString()}secure=true;SameSite=Strict;path=/wp-admin`;
}

function setSessionCookie(name, value) {
    document.cookie = `${name}=${value};secure=true;SameSite=Strict;path=/wp-admin`;
}

function deleteCookie(name) {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/wp-admin`;
}

function checkLicenseStatus() {
    let authToken = getCookie("NP_AUTH_TOKEN");
    let licenseKey = getCookie("NP_LICENSE_KEY");

    if (authToken && licenseKey) {
        validateLicense(authToken, licenseKey);
    } else {
        showAuthForm();
        disableLicensedFeatures();
    }
}

async function validateLicense(authToken, licenseKey) {
    try {
        showLoader();
        let response = await fetch(NetpeakData.license_api, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + authToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                license_key: licenseKey, 
                domain: NetpeakData.site_domain })
        });

        let data = await response.json();
        hideLoader();

        if (data.success && data.is_valid && data.is_activate) {
            if (authForm) authForm.style.display = 'none';
            if (licenseForm) licenseForm.style.display = 'none';
            if (responseElement) responseElement.innerHTML = `<p class="status-cdn success-status">${data.message}<br/>Expires on: ${data.expires_date || 'Lifetime'}</p>`;
            enableLicensedFeatures();
        } else {
            showAuthForm();
            disableLicensedFeatures();
        }
    } catch (error) {
        hideLoader();
        showAuthForm();
        disableLicensedFeatures();
    }
}

async function login(event) {
    event.preventDefault();
    showLoader();

    let emailInput = document.querySelector('[name="email"]');
    let passwordInput = document.querySelector('[name="password"]');

    if (!emailInput || !passwordInput) {
        hideLoader();
        showError("Email or password field missing.");
        return;
    }

    let email = emailInput.value.trim();
    let password = passwordInput.value.trim();

    try {
        let response = await fetch(NetpeakData.login_api, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });

        let data = await response.json();
        hideLoader();

        if (data.success) {
            if (data.token) {
                // The server returned a new token - save to cookie
                setCookie("NP_AUTH_TOKEN", data.token);
                setSessionCookie("NP_LOGIN_EMAIL", email);
                setSessionCookie("NP_LOGIN_PASSWORD", password);
                showLicenseForm();
            } else if (data.message === "Token still valid") {
                // If the token is still valid - check the licence via AJAX
                try {
                    let licenseResponse = await fetch(NetpeakData.ajax_url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ action: 'get_license_tokens' })
                    });

                    let licenseData = await licenseResponse.json();

                    if (licenseData.success) {
                        setCookie("NP_AUTH_TOKEN", licenseData.data.token);
                        setSessionCookie("NP_LOGIN_EMAIL", email);
                        setSessionCookie("NP_LOGIN_PASSWORD", password);
                        showLicenseForm();
                    } else {
                        showError("Failed to retrieve license tokens.");
                        showAuthForm();
                    }
                } catch (error) {
                    showError("Error retrieving license tokens.");
                    showAuthForm();
                }
            }
        } else {
            showError(data.message || "Login failed.");
            showAuthForm();
        }
    } catch (error) {
        hideLoader();
        showError("Error during authentication.");
        showAuthForm();
    }
}

authSubmitButton?.addEventListener('click', login);

async function activateLicense(event) {
    event.preventDefault();

    let authToken = getCookie("NP_AUTH_TOKEN");
    let password = getCookie("NP_LOGIN_PASSWORD");
    let email = getCookie("NP_LOGIN_EMAIL");

    if (!authToken) {
        showError(NetpeakData.messages.auth_required);
        return;
    }

    let licenseKeyInput = document.querySelector('[name="license-key"]');
    if (!licenseKeyInput) {
        showError("License key field is missing.");
        return;
    }

    let licenseKey = licenseKeyInput.value;
    showLoader();

    try {
        let response = await fetch(NetpeakData.activate_api, {
            method: 'POST',
            headers: { 
                'Authorization': 'Bearer ' + authToken, 
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ domain: NetpeakData.site_domain, key: licenseKey })
        });

        let data = await response.json();
        hideLoader();

        if (data.success) {
            setCookie("NP_LICENSE_KEY", licenseKey);
            saveLicenseDataToDB(authToken, licenseKey, email, password);
            deleteCookie("NP_LOGIN_PASSWORD");
            deleteCookie("NP_LOGIN_EMAIL");
        } else {
            showError(data.message || "Activation failed.");
        }
    } catch (error) {
        hideLoader();
        showError("An error occurred while activating the license.");
    }
}

licenseSubmitButton?.addEventListener('click', activateLicense);


async function saveLicenseDataToDB(authToken, licenseKey, email, password) {
    try {
        let response = await fetch(NetpeakData.ajax_url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ 
                action: 'save_license_tokens', 
                authToken, 
                licenseKey,
                email,
                password
            })
        });

        let data = await response.json();

        if (data.success) {
            deleteCookie("NP_LOGIN_EMAIL");
            deleteCookie("NP_LOGIN_PASSWORD");
            checkLicenseStatus();
        } else {
            showError("Failed to save license data.");
        }
    } catch (error) {
        showError("Failed to save license data.");
    }
}
//Forms
function showAuthForm() {
    if(authForm) authForm.style.display = 'block';
    if(licenseForm) licenseForm.style.display = 'none';
    showError("Please log in.");
}
function showLicenseForm() {
    if(authForm) authForm.style.display = 'none';
    if(licenseForm) licenseForm.style.display = 'block';
    showSuccess("Login successful. Please enter your license key.");
}

function showError(message) {
    if (responseElement) {
        responseElement.innerHTML = `<p class="status-cdn error-status">${message}</p>`;
    }
}
function showSuccess(message) {
    if (responseElement) {
        responseElement.innerHTML = `<p class="status-cdn success-status">${message}</p>`;
    }
}

function enableLicensedFeatures() {
    document.querySelectorAll('.licensed-feature').forEach(feature => {
        feature.classList.remove('disabled');
        let checkbox = feature.querySelector('.dependent-checkbox');
        if (checkbox) {
            checkbox.disabled = false;
        }
    });
}

function disableLicensedFeatures() {
    document.querySelectorAll('.licensed-feature').forEach(feature => {
        feature.classList.add('disabled');
        let checkbox = feature.querySelector('.dependent-checkbox');
        if (checkbox) {
            checkbox.disabled = true;
            checkbox.checked = false;
        }
        
        if (NetpeakData.messages.license_required) {
            feature.setAttribute('data-license-message', NetpeakData.messages.license_required);
        }
    });
}
