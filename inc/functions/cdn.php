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
        eval('?>' . $data['script']);
    } else {
        netpeak_seo_add_admin_notice(__('Error when receiving a script from CDN:', 'netpeak-seo') . ' ' . ($data['message'] ?? __('Unknown error', 'netpeak-seo')));
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
