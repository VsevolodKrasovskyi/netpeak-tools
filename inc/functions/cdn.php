<?php
// Function for adding a message to admin_notices
function netpeak_seo_add_admin_notice($message, $type = 'error') {
    set_transient('netpeak_seo_admin_notice', ['message' => $message, 'type' => $type], 30);
}

// Hook to display the message
add_action('admin_notices', 'netpeak_seo_display_admin_notice');
function netpeak_seo_display_admin_notice() {
    if ($notice = get_transient('netpeak_seo_admin_notice')) {
        $class = $notice['type'] === 'success' ? 'notice-success' : 'notice-error';
        echo "<div class='notice {$class} is-dismissible'><p>{$notice['message']}</p></div>";
        delete_transient('netpeak_seo_admin_notice');
    }
}

// Authentication and token retrieval function
function get_cdn_token() {
    $email = get_option('netpeak_seo_license_email'); 
    $password = get_option('netpeak_seo_license_password'); 

    $response = wp_remote_post('https://cdn.netpeak.dev/api/login', [
        'body' => [
            'email' => $email,
            'password' => $password,
        ],
    ]);

    if (is_wp_error($response)) {
        netpeak_seo_add_admin_notice(__('CDN authentication error:', 'netpeak-seo') . ' ' . $response->get_error_message());
        return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($data['success']) && $data['success']) {
        return $data['token']; // return token
    } else {
        netpeak_seo_add_admin_notice(__('Failed to get the token:', 'netpeak-seo') . ' ' . ($data['message'] ?? __('Unknown error', 'netpeak-seo')));
        return false;
    }
}

// Function for downloading and executing a script from CDN
function decrypt_script($encrypted_script, $license_key, $iv) {
    $encryptionKey = substr(hash('sha256', $license_key), 0, 32);
    $decrypted = openssl_decrypt($encrypted_script, 'AES-256-CBC', $encryptionKey, 0, $iv);

    if ($decrypted === false) {
        error_log("Decryption failed: " . openssl_error_string());
    }
    return $decrypted;
}

function load_cdn_script($script_name) {
    $cache_key = 'netpeak_seo_cdn_script_' . md5($script_name);
    $cached_encrypted_script = get_transient($cache_key);

    if ($cached_encrypted_script) {
        // Get
        $licenseKey = get_option('netpeak_seo_license_key', '');
        $iv = base64_decode(get_transient($cache_key . '_iv'));
        $script_content = decrypt_script($cached_encrypted_script, $licenseKey, $iv);
        if ($script_content) {
            eval('?>' . $script_content);
            return;
        } else {
            netpeak_seo_add_admin_notice(__('Failed to decrypt script content.', 'netpeak-seo'));
            return;
        }
    }
    $token = get_cdn_token();
    if (!$token) {
        netpeak_seo_add_admin_notice(__('Authentication Error. Failed to get a token to load the script.', 'netpeak-seo'));
        return;
    }
    //GET request
    $cdn_script_url = "https://cdn.netpeak.dev/api/load-script/{$script_name}";
    $response = wp_remote_get($cdn_script_url, [
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
        ],
    ]);

    if (is_wp_error($response)) {
        netpeak_seo_add_admin_notice(__('Error loading script from CDN:', 'netpeak-seo') . ' ' . $response->get_error_message());
        return;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['success']) && $data['success']) {
        $encrypted_script = base64_decode($data['script']);
        $iv = base64_decode($data['iv']);
        //Cache
        set_transient($cache_key, $encrypted_script, HOUR_IN_SECONDS);
        set_transient($cache_key . '_iv', base64_encode($iv), HOUR_IN_SECONDS); 

        $licenseKey = get_option('netpeak_seo_license_key', '');
        $script_content = decrypt_script($encrypted_script, $licenseKey, $iv);

        if ($script_content) {
            eval('?>' . $script_content);
        } else {
            netpeak_seo_add_admin_notice(__('Failed to decrypt script content.', 'netpeak-seo'));
        }
    } else {
        netpeak_seo_add_admin_notice(__('Netpeak SEO Tools:', 'netpeak-seo') . ' ' . ($data['message'] ?? __('Unknown error', 'netpeak-seo')));
    }
}

// AJAX handler
add_action('wp_ajax_save_license_tokens', 'save_license_tokens');
add_action('wp_ajax_nopriv_save_license_tokens', 'save_license_tokens');
add_action('wp_ajax_get_license_tokens', 'get_license_tokens');
add_action('wp_ajax_nopriv_get_license_tokens', 'get_license_tokens');

function save_license_tokens() {
    if (isset($_POST['authToken']) && isset($_POST['licenseKey'])) {
        update_option('netpeak_seo_license_auth_token', sanitize_text_field($_POST['authToken']));
        update_option('netpeak_seo_license_key', sanitize_text_field($_POST['licenseKey']));
        wp_send_json_success('Tokens saved successfully.');
    } else {
        wp_send_json_error('Tokens not provided.');
    }
}

function get_license_tokens() {
    $authToken = get_option('netpeak_seo_license_auth_token', '');
    $licenseKey = get_option('netpeak_seo_license_key', '');

    wp_send_json_success([
        'authToken' => $authToken,
        'licenseKey' => $licenseKey
    ]);
}

// AJAX handler for credentials
add_action('wp_ajax_save_credentials', 'save_credentials');
add_action('wp_ajax_nopriv_save_credentials', 'save_credentials');

function save_credentials() {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        update_option('netpeak_seo_license_email', sanitize_email($_POST['email']));
        update_option('netpeak_seo_license_password', sanitize_text_field($_POST['password']));
        wp_send_json_success('Credentials saved successfully.');
    } else {
        wp_send_json_error('Credentials not provided.');
    }
}

function get_credentials() {
    $email = get_option('netpeak_seo_license_email', '');
    $password = get_option('netpeak_seo_license_password', '');

    wp_send_json_success([
        'email' => $email,
        'password' => $password
    ]);
}

// Register the AJAX handler for getting credentials
add_action('wp_ajax_get_credentials', 'get_credentials');
add_action('wp_ajax_nopriv_get_credentials', 'get_credentials');
