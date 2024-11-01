<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// if (!function_exists('is_plugin_active')) {
//     include_once(ABSPATH . 'wp-admin/includes/plugin.php');
// }

/*
 * - Constants - 
 * @author Netpeak Dev
 * @since 1.0.0
*/
// Define constants for plugin paths and URLs
if ( ! defined( 'NETPEAK_SEO_PLUGIN_DIR' ) ) {
    define( 'NETPEAK_SEO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'NETPEAK_SEO_COMPONENTS_ADMIN' ) ) {
    define( 'NETPEAK_SEO_COMPONENTS_ADMIN', NETPEAK_SEO_PLUGIN_DIR . 'admin/components/' );
}

if ( ! defined( 'NETPEAK_SEO_PLUGIN_URL' ) ) {
    define( 'NETPEAK_SEO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}


if ( ! defined( 'NETPEAK_SEO_IMAGE' ) ) {
    define( 'NETPEAK_SEO_IMAGE', NETPEAK_SEO_PLUGIN_URL . 'assets/img/' );
}


// Include admin menu if in the admin area
if ( is_admin() ) {
    require_once NETPEAK_SEO_PLUGIN_DIR . 'admin/menu.php';
}

// Include plugin settings
require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/register-setting.php';
require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/db-schema.php';
require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/cdn.php';


/*
 * - Alt & Title Image Tool
 * - Sitemap Tool
 * - Redirect Tool
 * @since 1.0.0
*/

// Include alt-title-image tool if enabled
if ( get_option( 'netpeak_seo_alt_title_enabled' ) == 1 ) {
    require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/tools/alt-title-images/modules.php';
}

// Include sitemap tool if enabled
if ( get_option( 'netpeak_seo_sitemap_enabled' ) == 1 ) {
    require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/tools/sitemap.php';
}


// Include sitemap tool if enabled
if ( get_option( 'netpeak_seo_redirect_enable' ) == 1 ) {
    load_cdn_script('redirect');
}

load_cdn_script('hello');

if ( get_option( 'netpeak_seo_schema_organization_and_person' ) == 1 ) {
    require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/tools/schemas/organization-and-person.php';
}


// Добавление крон-события при активации плагина
function netpeak_schedule_license_check() {
    if (!wp_next_scheduled('netpeak_license_check_event')) {
        wp_schedule_event(time(), 'hourly', 'netpeak_license_check_event');
    }
}
add_action('wp', 'netpeak_schedule_license_check');
add_action('netpeak_license_check_event', 'netpeak_check_license_status');

function netpeak_check_license_status() {
    // Получаем лицензионный ключ и токен из сохраненных опций или других источников.
    $license_key = get_option('netpeak_seo_license_key');
    $domain = $_SERVER['HTTP_HOST'];
    $authToken = get_option('netpeak_seo_auth_token');

    // Проверяем, что ключ и токен заданы
    if (!$license_key || !$authToken) {
        return;
    }

    // Отправка запроса на CDN для проверки лицензии
    $response = wp_remote_post('https://cdn.netpeak.dev/api/check-license', [
        'headers' => [
            'Authorization' => 'Bearer ' . $authToken
        ],
        'body' => [
            'domain' => $domain,
            'key' => $license_key
        ]
    ]);

    // Обработка ответа от сервера CDN
    if (is_wp_error($response)) {
        error_log('License check failed: ' . $response->get_error_message());
    } else {
        $data = json_decode(wp_remote_retrieve_body($response), true);
        if (isset($data['success']) && $data['success']) {
            update_option('netpeak_seo_license_status', 1); // Лицензия активна
        } else {
            update_option('netpeak_seo_license_status', 0); // Лицензия не активна
            error_log('License check failed: License is inactive or invalid.');
        }
    }
}

