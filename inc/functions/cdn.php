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
function load_cdn_script($script_name) {
    $cacheManager = new \NetpeakSEO\CacheManager(WP_CONTENT_DIR . '/netpeak_seo_cache/cdn');

    $license_key = get_option('netpeak_seo_license_key');
    if (!$license_key) {
        netpeak_seo_add_admin_notice(__('License key is missing. Unable to load the script.', 'netpeak-seo'));
        return;
    }

    $cache_key = 'netpeak_seo_cdn_script_' . md5($script_name);
    $cached_data = $cacheManager->get($cache_key);

    // If the script is in the cache, check its integrity and use
    if ($cached_data) {
        $cached_hash = $cacheManager->get($cache_key . '_hash');
        $decrypted_script = openssl_decrypt($cached_data, 'AES-256-CBC', $license_key, 0, substr($license_key, 0, 16));

        if ($decrypted_script && hash('sha256', $decrypted_script) === $cached_hash) {
            //netpeak_seo_add_admin_notice(__('Using cached script.', 'netpeak-seo'));
            eval('?>' . $decrypted_script);
            return;
        }
    }

    // If the script is not found in the cache, request it from the CDN
    $token = get_cdn_token();
    if (!$token) {
        netpeak_seo_add_admin_notice(__('Authentication Error. Failed to get a token to load the script.', 'netpeak-seo'));
        return;
    }

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
        $script_content_base64 = $data['script'];
        $script_content = base64_decode($script_content_base64);
        
        $encrypted_script = openssl_encrypt($script_content, 'AES-256-CBC', $license_key, 0, substr($license_key, 0, 16));
        $hash = hash('sha256', $script_content);
        // Save the encrypted script and its hash to the cache
        $cacheManager->set($cache_key, $encrypted_script, HOUR_IN_SECONDS);
        $cacheManager->set($cache_key . '_hash', $hash, HOUR_IN_SECONDS);

        eval('?>' . $script_content);
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
