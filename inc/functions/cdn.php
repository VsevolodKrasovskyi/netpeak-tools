<?php

// Get tocket auth
function get_cdn_token() {
    $email = 'sevakrasovskiy@gmail.com'; 
    $password = 'password'; 
    
    $response = wp_remote_post('https://cdn.netpeak.dev/api/login', [
        'body' => [
            'email' => $email,
            'password' => $password,
        ],
    ]);

    if (is_wp_error($response)) {
        error_log('Ошибка аутентификации на CDN: ' . $response->get_error_message());
        return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($data['success']) && $data['success']) {
        return $data['token']; // return tocken
    } else {
        error_log('еН удалось получить токен: ' . ($data['message'] ?? 'Неизвестная ошибка'));
        return false;
    }
}

// Load Content
function load_cdn_script($script_name) {
    $token = get_cdn_token();
    if (!$token) {
        echo 'Ошибка аутентификации. Не удалось получить токен для загрузки скрипта.';
        return;
    }

    $cdn_script_url = "https://cdn.netpeak.dev/api/load-script/{$script_name}";

    $response = wp_remote_get($cdn_script_url, [
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
        ],
    ]);

    if (is_wp_error($response)) {
        error_log('Ошибка загрузки скрипта с CDN: ' . $response->get_error_message());
        echo 'Ошибка загрузки скрипта с CDN.';
        return;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['success']) && $data['success']) {
        eval('?>' . $data['script']);
    } else {
        error_log('Ошибка при получении скрипта с CDN: ' . ($data['message'] ?? 'Неизвестная ошибка'));
        echo 'Ошибка при получении скрипта с CDN.';
    }
}