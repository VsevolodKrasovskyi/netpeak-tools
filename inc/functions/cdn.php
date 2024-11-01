<?php
// Function for authentication and token retrieval
function get_cdn_token() {
    $email = get_option( 'netpeak_seo_license_email' ); 
    $password = get_option( 'netpeak_seo_license_password' ); 

    $response = wp_remote_post('https://cdn.netpeak.dev/api/login', [
        'body' => [
            'email' => $email,
            'password' => $password,
        ],
    ]);

    if (is_wp_error($response)) {
        error_log('CDN authentication error: ' . $response->get_error_message());
        return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($data['success']) && $data['success']) {
        return $data['token']; // return token
    } else {
        error_log('Failed to get the token: ' . ($data['message'] ?? 'Unknown error'));
        return false;
    }
}

// Function for downloading and executing a script from CDN
function load_cdn_script($script_name) {
    $token = get_cdn_token();
    if (!$token) {
        echo 'Authentication Error. Failed to get a token to load the script.';
        return;
    }

    $cdn_script_url = "https://cdn.netpeak.dev/api/load-script/{$script_name}";

    $response = wp_remote_get($cdn_script_url, [
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
        ],
    ]);

    if (is_wp_error($response)) {
        error_log('Error loading script from CDN: ' . $response->get_error_message());
        echo 'Error loading script from CDN.';
        return;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['success']) && $data['success']) {
        eval('?>' . $data['script']);
    } else {
        error_log('Error when receiving a script from CDN: ' . ($data['message'] ?? 'Неизвестная ошибка'));
        echo 'Error when receiving a script from CDN.';
    }
}
