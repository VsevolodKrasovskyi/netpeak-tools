<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include admin menu if in the admin area
if ( is_admin() ) {
    require_once NETPEAK_SEO_PLUGIN_DIR . 'admin/menu.php';
}

// Include plugin settings
require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/register-setting.php';
require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/db-schema.php';
require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/cdn.php';
require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/CacheManager.php';
require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/AjaxHandler.php';

//Include Class
use NetpeakTools\CDN;
$cdn = CDN::getInstance();

function netpeak_load_assets() {
    $cdn = CDN::getInstance();
    wp_enqueue_script('netpeak-license', NETPEAK_SEO_PLUGIN_URL . 'assets/js/license.js', array(), NETPEAK_SEO_VERSION, true);
    wp_localize_script('netpeak-license', 'NetpeakData', [
        'ajax_url'      => admin_url('admin-ajax.php'),
        'site_domain'   => parse_url(home_url(), PHP_URL_HOST),
        'license_text'  => __('License is active and valid.', 'netpeak-seo'),
        'login_api'     => $cdn->getBaseApi() . 'login',
        'license_api'   => $cdn->getBaseApi() . 'check-license-status',
        'activate_api'  => $cdn->getBaseApi() . 'activate-license',
        'auth_token' => get_option('netpeak_seo_license_auth_token'),
        'license_key' => get_option('netpeak_seo_license_key'),
        'messages'      => [
            'expires_on'      => __('Expires on:', 'netpeak-seo'),
            'lifetime'        => __('Lifetime license.', 'netpeak-seo'),
            'auth_required'   => __('Authorization required. Please log in again.', 'netpeak-seo'),
            'invalid_license' => __('License is invalid. Please contact support.', 'netpeak-seo'),
            'not_found'       => __('License not found. Please check your license key.', 'netpeak-seo'),
            'generic_error'   => __('An error occurred while checking the license status.', 'netpeak-seo'),
            'error_token'     =>__('Error retrieving tokens','netpeak-seo'),
            'license_required' => __('License is required to use this feature.', 'netpeak-seo'),
        ],
    ]);
    wp_enqueue_style( 'netpeak-admin-style', NETPEAK_SEO_PLUGIN_URL . 'assets/css/admin.css' );
    wp_enqueue_style( 'netpeak-license-page', NETPEAK_SEO_PLUGIN_URL . 'assets/css/license-page.css' );
    wp_enqueue_script( 'netpeak-tooltip', NETPEAK_SEO_PLUGIN_URL . 'assets/js/tooltip.js', array(), NETPEAK_SEO_VERSION, true );
    wp_enqueue_script( 'netpeak-dependent-checkbox', NETPEAK_SEO_PLUGIN_URL . 'assets/js/dependent-checkbox.js', array(), NETPEAK_SEO_VERSION, true );
    wp_enqueue_style('netpeak-license-switch-css', NETPEAK_SEO_PLUGIN_URL . 'assets/css/license-switch.css');
};
add_action('admin_enqueue_scripts', 'netpeak_load_assets');
function add_module_type($tag, $handle, $src) {
    if ('netpeak-license' === $handle) {
        $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
    }
    return $tag;
}
add_filter('script_loader_tag', 'add_module_type', 10, 3);

/*
 * - Alt & Title Image Tool
 * - Sitemap Tool
 * - Redirect Tool
 * - Mail Settings
 * @since 1.0.2
*/

$cdn_options = [
    'netpeak_smtp_enabled' => 'mail',
    'netpeak_seo_alt_title_generate_elementor' => 'elementor',
];

foreach ($cdn_options as $option => $script) {
    if (get_option($option) == 1) {
        $cdn->load_cdn_script($script);
    }
}

$tools = [
    'netpeak_seo_sitemap_enabled' => 'inc/tools/sitemap.php',
    'netpeak_seo_redirect_enable' => 'inc/tools/redirect.php',
    'netpeak_seo_schema_organization_and_person' => 'inc/tools/schemas/organization-and-person.php',
];

foreach ($tools as $option => $file) {
    if (get_option($option) == 1) {
        require_once NETPEAK_SEO_PLUGIN_DIR . $file;
    }
}




