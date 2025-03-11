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
$cdn = new CDN();

function netpeak_load_assets() {
    $cdn = new \NetpeakTools\CDN(); 
    wp_enqueue_script('netpeak-license', NETPEAK_SEO_PLUGIN_URL . 'assets/js/license.js', array(), null, true);
    wp_localize_script('netpeak-license', 'NetpeakData', [
        'ajax_url'      => admin_url('admin-ajax.php'),
        'site_domain'   => parse_url(home_url(), PHP_URL_HOST),
        'license_text'  => __('License is active and valid.', 'netpeak-seo'),
        'login_api'     => $cdn->getBaseApi() . 'login',
        'license_api'   => $cdn->getBaseApi() . 'check-license-status',
        'activate_api'  => $cdn->getBaseApi() . 'activate-license',
        'messages'      => [
            'expires_on'      => __('Expires on:', 'netpeak-seo'),
            'lifetime'        => __('Lifetime license.', 'netpeak-seo'),
            'auth_required'   => __('Authorization required. Please log in again.', 'netpeak-seo'),
            'invalid_license' => __('License is invalid. Please contact support.', 'netpeak-seo'),
            'not_found'       => __('License not found. Please check your license key.', 'netpeak-seo'),
            'generic_error'   => __('An error occurred while checking the license status.', 'netpeak-seo'),
            'error_token'     =>__('Error retrieving tokens','netpeak-seo')
        ]
    ]);
    wp_enqueue_style( 'netpeak-admin-style', NETPEAK_SEO_PLUGIN_URL . 'assets/css/admin.css' );
    wp_enqueue_style( 'netpeak-license-page', NETPEAK_SEO_PLUGIN_URL . 'assets/css/license-page.css' );
    wp_enqueue_script( 'netpeak-tooltip', NETPEAK_SEO_PLUGIN_URL . 'assets/js/tooltip.js', array(), NETPEAK_SEO_VERSION, true );
    wp_enqueue_script( 'netpeak-dependent-checkbox', NETPEAK_SEO_PLUGIN_URL . 'assets/js/dependent-checkbox.js', array(), NETPEAK_SEO_VERSION, true );
};
add_action('admin_enqueue_scripts', 'netpeak_load_assets');



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
    'netpeak_seo_alt_title_auto_enabled' => 'auto-alt-title',
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




