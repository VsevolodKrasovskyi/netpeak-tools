document.addEventListener('DOMContentLoaded', initLicenseHandler);

function initLicenseHandler() {
    checkLicenseStatus(); 
}


const loaderElement = document.getElementById('loader');
const responseElement = document.getElementById('netpeak-license-response');

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


function checkLicenseStatus() {
    let authToken = localStorage.getItem("authToken");
    let licenseKey = localStorage.getItem("licenseKey");

    if (authToken && licenseKey) {
        validateLicense(authToken, licenseKey);
    } else {
        showLoader();
        fetch(NetpeakData.ajax_url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'get_license_tokens' })
        })
        .then(response => response.json())
        .then(data => {
            hideLoader();
            if (data.success) {
                authToken = data.data.authToken;
                licenseKey = data.data.licenseKey;

                if (authToken && licenseKey) {
                    localStorage.setItem("authToken", authToken);
                    localStorage.setItem("licenseKey", licenseKey);
                    validateLicense(authToken, licenseKey);
                } else {
                    document.getElementById('auth-form').style.display = 'block';
                    document.getElementById('license-form').style.display = 'none';
                }
            } else {
                showError('Failed to retrieve tokens');
            }
        })
        .catch(() => {
            hideLoader();
            showError(NetpeakData.message.error_token);
        });
    }
}


/**
 * Validate the license by making an API request
 *
 * @param {string} authToken The authentication token
 * @param {string} licenseKey The license key
 *
 * @throws {Error} On server error
 */
async function validateLicense(authToken, licenseKey) {
    try {
        showLoader();

        let response = await fetch(NetpeakData.license_api, {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + authToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                license_key: licenseKey,
                domain: NetpeakData.site_domain
            })
        });

        let data = await response.json();
        hideLoader();

        const authForm = document.getElementById('auth-form');
        const licenseForm = document.getElementById('license-form');

        if (data.success && data.is_valid && data.is_activate) {
            if (authForm) authForm.style.display = 'none';
            if (licenseForm) licenseForm.style.display = 'none';
            responseElement.innerHTML = `
                <p class="status-cdn success-status">
                    ${data.message}
                    ${data.expires_date ? '</br>' + NetpeakData.messages.expires_on + ' ' + data.expires_date : NetpeakData.messages.lifetime}
                </p>`;
        } else {
            showError(data.message);
            
            authForm.style.display = 'block';
        }
    } catch (error) {
        hideLoader();
        showError(data.message);
    }
}


async function authenticateUser(event) {
    event.preventDefault();
    showLoader();
    let email = document.querySelector('[name="email"]').value;
    let password = document.querySelector('[name="password"]').value;

    try {
        let response = await fetch(NetpeakData.login_api, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });

        let data = await response.json();
        hideLoader();

        if (data.success) {
            localStorage.setItem("authToken", data.token);
            saveCredentials(email, password);
            document.getElementById('auth-form').style.display = 'none';
            document.getElementById('license-form').style.display = 'block';
            responseElement.innerHTML = `<p class="status-cdn success-status">Authentication successful. Please enter your license key.</p>`;
        } else {
            showError(data.message);
        }
    } catch {
        hideLoader();
        showError("Error during authentication.");
    }
}
const authSubmitButton = document.getElementById('auth-submit');

if (authSubmitButton) {
    authSubmitButton.addEventListener('click', authenticateUser);
}



async function activateLicense(event) {
    event.preventDefault();

    let authToken = localStorage.getItem("authToken");
    if (!authToken) {
        showError(NetpeakData.messages.auth_required);
        return;
    }

    let licenseKey = document.querySelector('[name="license-key"]').value;
    showLoader();

    try {
        let response = await fetch(NetpeakData.activate_api, {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + authToken, 'Content-Type': 'application/json' },
            body: JSON.stringify({ domain: NetpeakData.site_domain, key: licenseKey })
        });

        let data = await response.json();
        hideLoader();

        if (data.success) {
            saveTokens(authToken, licenseKey);
            checkLicenseStatus();
            responseElement.innerHTML = `<p class="status-cdn success-status">License verification successful: License is valid and activated.</p>`;
        } else {
            showError(data.message || NetpeakData.messages.invalid_license);
        }
    } catch {
        hideLoader();
        showError("An error occurred while activating the license.");
    }
}
const licenseSubmit = document.getElementById('license-submit')
if (licenseSubmit) {
    licenseSubmit.addEventListener('click', activateLicense);
}


async function saveTokens(authToken, licenseKey) {
    if (authToken) localStorage.setItem("authToken", authToken);
    if (licenseKey) localStorage.setItem("licenseKey", licenseKey);

    try {
        await fetch(NetpeakData.ajax_url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: 'save_license_tokens',
                authToken,
                licenseKey
            })
        });
    } catch {
        showError("Failed to save tokens.");
    }
}


function removeTokens() {
    localStorage.removeItem("authToken");
    localStorage.removeItem("licenseKey");
    saveTokens('', '');
}

async function saveCredentials(email, password) {
    if (email) localStorage.setItem("email", email);
    if (password) localStorage.setItem("password", password);

    try {
        await fetch(NetpeakData.ajax_url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: 'save_credentials',
                email,
                password
            })
        });
    } catch {
        showError("Failed to save credentials.");
    }
}

function showError(message) {
    if (responseElement)
    {
        responseElement.innerHTML = `<p class="status-cdn error-status">${message}</p>`;
    }
}

function get_credentials() {
    let email = localStorage.getItem("email");
    let password = localStorage.getItem("password");
    

    fetch(NetpeakData.ajax_url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'get_credentials' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.setItem("email", data.data.email);
            localStorage.setItem("password", data.data.password);
        }
    })
    .catch(() => {
        showError("Error retrieving credentials.");
    });

    return { email, password };
}
