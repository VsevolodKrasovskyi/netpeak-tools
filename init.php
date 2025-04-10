<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use NetpeakManager\CDN;

// Include admin menu if in the admin area
if ( is_admin() ) {
    require_once NETPEAK_SEO_PLUGIN_DIR . 'admin/menu.php';
}

// Include plugin settings
require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/register-setting.php';
require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/db-schema.php';
require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/AjaxHandler.php';
include NETPEAK_SEO_COMPONENTS_ADMIN . 'widget-dashboard.php';

function netpeak_tools_load_assets() {
    wp_enqueue_style( 'netpeak-admin-style', NETPEAK_SEO_PLUGIN_URL . 'assets/css/admin.css' );
    wp_enqueue_script( 'netpeak-tooltip', NETPEAK_SEO_PLUGIN_URL . 'assets/js/tooltip.js', array(), NETPEAK_SEO_VERSION, true );
    wp_enqueue_script( 'netpeak-dependent-checkbox', NETPEAK_SEO_PLUGIN_URL . 'assets/js/dependent-checkbox.js', array(), NETPEAK_SEO_VERSION, true );
    wp_enqueue_style('netpeak-license-switch-css', NETPEAK_SEO_PLUGIN_URL . 'assets/css/license-switch.css');
};
add_action('admin_enqueue_scripts', 'netpeak_tools_load_assets');

add_action('plugins_loaded', function () {
    if (!class_exists('\NetpeakManager\CDN')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p><strong>Netpeak Manager</strong> plugin must be activated for this plugin to function.</p></div>';
        });
        return;
    }
    $cdn = CDN::getInstance();
    $cdn_options = [
        'netpeak_smtp_enabled' => 'mail'
    ];
    foreach ($cdn_options as $option => $script) {
        if (get_option($option) == 1) {
            $cdn->load_cdn_script($script);
        }
    }
});

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




