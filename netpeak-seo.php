<?php
/**
 * Plugin Name: Netpeak SEO Tools
 * Description: Basic SEO settings, HTML map generation, and more
 * Author: Netpeak Dev Team
 * Author URI: https://netpeak.bg/
 * Text Domain: netpeak-seo
 * Domain Path: /languages
 * Requires at least: 5.7
 * Requires PHP: 7.0
 * License: GPL2
 * License URI: https://cdn.netpeak.dev/
 * Version: 1.0.0
 */

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

