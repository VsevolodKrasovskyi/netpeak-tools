<?php
/**
 * Plugin Name: Netpeak Tools
 * Plugin URI: https://cdn.netpeak.dev/
 * Description: Basic SEO settings, HTML map generation, and more
 * Author: Netpeak Dev Team
 * Author URI: https://netpeak.dev/
 * Text Domain: netpeak-seo
 * Domain Path: /languages
 * Requires at least: 5.7
 * Requires PHP: 7.0
 * License: Subscription-based License
 * License URI: https://cdn.netpeak.dev/license-information
 * Version: 1.0.3
 * ███╗   ██╗███████╗████████╗██████╗ ███████╗ █████╗ ██╗  ██╗
 * ████╗  ██║██╔════╝╚══██╔══╝██╔══██╗██╔════╝██╔══██╗██║ ██╔╝
 * ██╔██╗ ██║█████╗     ██║   ██████╔╝█████╗  ███████║█████╔╝ 
 * ██║╚██╗██║██╔══╝     ██║   ██╔═══╝ ██╔══╝  ██╔══██║██╔═██╗ 
 * ██║ ╚████║███████╗   ██║   ██║     ███████╗██║  ██║██║  ██╗
 * ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝     ╚══════╝╚═╝  ╚═╝╚═╝  ╚═╝
 */
if ( ! class_exists( 'WP_GitHub_Updater' ) ) {
    include_once plugin_dir_path( __FILE__ ) . 'inc/functions/updater.php'; 
}

if ( class_exists( 'WP_GitHub_Updater' ) ) {
    new WP_GitHub_Updater(array(
        'slug' => plugin_basename( __FILE__ ),
        'proper_folder_name' => dirname( plugin_basename( __FILE__ ) ),
        'api_url' => 'https://api.github.com/repos/VsevolodKrasovskyi/netpeak-tools', 
        'raw_url' => 'https://raw.githubusercontent.com/VsevolodKrasovskyi/netpeak-tools/prod', 
        'github_url' => 'https://github.com/VsevolodKrasovskyi/netpeak-tools', 
        'zip_url' => 'https://github.com/VsevolodKrasovskyi/netpeak-tools/zipball/prod', 
        'sslverify' => true, 
        'requires' => '3.0', 
        'tested' => '6.0', 
        'readme' => 'README.md', 
        'access_token' => '', 
        'screenshots' => array(
            'https://raw.githubusercontent.com/VsevolodKrasovskyi/netpeak-tools/prod/changelog/screenshots/screenshot1.png',
            'https://raw.githubusercontent.com/VsevolodKrasovskyi/netpeak-tools/prod/changelog/screenshots/screenshot2.png',
        ),
    ));
}


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require plugin_dir_path( __FILE__ ) . 'init.php';


/*
* Load plugin textdomain.
*/
function netpeak_load_textdomain() {
  load_plugin_textdomain( 'netpeak-seo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action( 'init', 'netpeak_load_textdomain' );

// Hooks
register_activation_hook(__FILE__, 'create_schema_table');
