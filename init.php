<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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
require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/CacheManager.php';
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
        load_cdn_script($script);
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




