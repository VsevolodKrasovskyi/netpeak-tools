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
 * Version: 1.0.11
 * ███╗   ██╗███████╗████████╗██████╗ ███████╗ █████╗ ██╗  ██╗
 * ████╗  ██║██╔════╝╚══██╔══╝██╔══██╗██╔════╝██╔══██╗██║ ██╔╝
 * ██╔██╗ ██║█████╗     ██║   ██████╔╝█████╗  ███████║█████╔╝ 
 * ██║╚██╗██║██╔══╝     ██║   ██╔═══╝ ██╔══╝  ██╔══██║██╔═██╗ 
 * ██║ ╚████║███████╗   ██║   ██║     ███████╗██║  ██║██║  ██╗
 * ╚═╝  ╚═══╝╚══════╝   ╚═╝   ╚═╝     ╚══════╝╚═╝  ╚═╝╚═╝  ╚═╝
 */

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
if ( ! defined( 'NETPEAK_SEO_VERSION' ) ) {
    define( 'NETPEAK_SEO_VERSION', '1.0.11' );
}

use Netpeak\Updater_GIT;

if (file_exists(NETPEAK_SEO_PLUGIN_DIR . '/vendor/autoload.php')) {
    require_once NETPEAK_SEO_PLUGIN_DIR . '/vendor/autoload.php';
}

function load_updater_netpeak_tools(){
    new Updater_GIT(array(
        'slug' => plugin_basename( __FILE__ ),
        'proper_folder_name' => dirname( plugin_basename( __FILE__ ) ),
        'api_url' => 'https://api.github.com/repos/VsevolodKrasovskyi/netpeak-tools', 
        'raw_url' => 'https://raw.githubusercontent.com/VsevolodKrasovskyi/netpeak-tools/prod', 
        'github_url' => 'https://github.com/VsevolodKrasovskyi/netpeak-tools', 
        'zip_url' => 'https://github.com/VsevolodKrasovskyi/netpeak-tools/zipball/prod', 
        'sslverify' => true, 
        'requires' => '5.2', 
        'tested' => '6.7.1', 
        'readme' => 'README.md', 
        'screenshots' => array(
            'https://raw.githubusercontent.com/VsevolodKrasovskyi/netpeak-tools/prod/changelog/screenshots/screenshot1.png',
            'https://raw.githubusercontent.com/VsevolodKrasovskyi/netpeak-tools/prod/changelog/screenshots/screenshot2.png',
            'https://raw.githubusercontent.com/VsevolodKrasovskyi/netpeak-tools/prod/changelog/screenshots/screenshot3.png',
        ),
        'banner'=> 'https://images.netpeak.net/blog/main_691d938eb457d4bc06eae9c59d8cc216c3a161c8.png'
    ));
}
add_action('admin_init', 'load_updater_netpeak_tools'); 

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
add_action( 'plugins_loaded', 'netpeak_load_textdomain' );

