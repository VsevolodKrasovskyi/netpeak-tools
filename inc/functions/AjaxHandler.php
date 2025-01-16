<?php
//SCHEMA & STRUCTURE
function load_schema_tab() {
    $tab = isset($_POST['tab']) ? sanitize_text_field($_POST['tab']) : '';
    

    if ($tab === '') {
        include NETPEAK_SEO_COMPONENTS_ADMIN . 'schemas/intro.php';
    } elseif ($tab === 'organization-and-person') {
        include NETPEAK_SEO_COMPONENTS_ADMIN . 'schemas/organization-and-person.php';
    } else {
        echo '<p>Invalid tab.</p>';
    }

    wp_die();
}
add_action( 'wp_ajax_load_schema_tab', 'load_schema_tab' );

// AJAX handler for license tokens
add_action('wp_ajax_save_license_tokens', 'save_license_tokens');
add_action('wp_ajax_get_license_tokens', 'get_license_tokens');

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
add_action('wp_ajax_get_credentials', 'get_credentials');

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



