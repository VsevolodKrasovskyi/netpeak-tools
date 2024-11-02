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

if ( get_option( 'netpeak_seo_schema_organization_and_person' ) == 1 ) {
    require_once NETPEAK_SEO_PLUGIN_DIR . 'inc/tools/schemas/organization-and-person.php';
}