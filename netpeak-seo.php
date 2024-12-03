<?php
/**
 * Plugin Name: Netpeak Tools
 * Description: Basic SEO settings, HTML map generation, and more
 * Author: Netpeak Dev Team
 * Author URI: https://netpeak.dev/
 * Text Domain: netpeak-seo
 * Domain Path: /languages
 * Requires at least: 5.7
 * Requires PHP: 7.0
 * License: Subscription-based License
 * License URI: https://cdn.netpeak.dev/license-information
 * Version: 1.0.2
 */
if (is_admin()) {
    define('GH_REQUEST_URI', 'https://api.github.com/repos/%s/%s/releases');
    define('GHPU_USERNAME', 'VsevolodKrasovskyi'); 
    define('GHPU_REPOSITORY', 'netpeak-tools'); 
    
    include_once plugin_dir_path(__FILE__) . 'inc/functions/updater.php';  

    $updater = new GhPluginUpdater(__FILE__);
    $updater->init();
}



if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require plugin_dir_path( __FILE__ ) . 'init.php';

function netpeak_seo_register_textdomain() {
    $domain = 'netpeak-seo';
    $locale = determine_locale();
    $mofile = plugin_dir_path( __FILE__ ) . "languages/{$domain}-{$locale}.mo";
    if ( file_exists( $mofile ) ) {
        load_textdomain( $domain, $mofile );
    }
}
add_action( 'plugins_loaded', 'netpeak_seo_register_textdomain', 1000 );

// Hooks
register_activation_hook(__FILE__, 'create_schema_table');
