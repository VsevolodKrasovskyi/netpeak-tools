<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function netpeak_seo_add_admin_menu() {
    add_menu_page(
        __( 'Netpeak SEO', 'netpeak-seo' ),              
        __( 'Netpeak SEO', 'netpeak-seo' ),              
        'manage_options',                                
        'netpeak-seo-main',                              
        'netpeak_seo_alt_title_page',                    
        NETPEAK_SEO_IMAGE . 'netpeak-icon.svg',
    );

    // Tab Alt&Title Image
    add_submenu_page(
        'netpeak-seo-main',     
        __( 'Alt&Title Image', 'netpeak-seo' ),          
        __( 'Alt&Title Image', 'netpeak-seo' ),          
        'manage_options',                                
        'netpeak-seo-alt-title',                        
        'netpeak_seo_alt_title_page'                     
    );

    // Tab HTML Maps
    add_submenu_page(
        'netpeak-seo-main',                       
        __( 'HTML Maps', 'netpeak-seo' ),                
        __( 'HTML Maps', 'netpeak-seo' ),                
        'manage_options',                                
        'netpeak-seo-html-maps',                         
        'netpeak_seo_html_maps_page'                     
    );

    // Tab Basic Redirect
    add_submenu_page(
        'netpeak-seo-main',                             
        __( 'Basic Redirect', 'netpeak-seo' ),           
        __( 'Basic Redirect', 'netpeak-seo' ),           
        'manage_options',                                
        'netpeak-seo-basic-redirect',                    
        'netpeak_seo_basic_redirect'                     
    );

    // Tab Schema & Structure
    add_submenu_page(
        'netpeak-seo-main',                          
        __( 'Schema & Structure', 'netpeak-seo' ),           
        __( 'Schema & Structure', 'netpeak-seo' ),           
        'manage_options',                                
        'netpeak-schema-and-structure', // URL           
        'schema_and_structure_page'
    );
    //License
    add_submenu_page( 
        'netpeak-seo-main',
        __( 'License', 'netpeak-seo' ),           
        __( 'License', 'netpeak-seo' ),  
        'manage_options',
        'netpeak-seo-tools-license',
        'license_page'
    );
}
add_action( 'admin_menu', 'netpeak_seo_add_admin_menu' );


/*
 * @category tabs
 * @author Netpeak Dev
*/
function netpeak_seo_alt_title_page() {
    include NETPEAK_SEO_PLUGIN_DIR . 'admin/tabs/alt-title-image.php';
}

function netpeak_seo_html_maps_page() {
    include NETPEAK_SEO_PLUGIN_DIR . 'admin/tabs/sitemap-html.php';
}

function netpeak_seo_basic_redirect() {
    include NETPEAK_SEO_PLUGIN_DIR . 'admin/tabs/redirect.php';
}

function schema_and_structure_page() {
    include NETPEAK_SEO_PLUGIN_DIR . 'admin/tabs/schemas.php';
}

function license_page() {
    include NETPEAK_SEO_PLUGIN_DIR . 'admin/tabs/license.php';
}

//Style & Script
function netpeak_assets_include_admin() {
    wp_enqueue_style( 'netpeak-admin-style', NETPEAK_SEO_PLUGIN_URL . 'assets/css/admin.css' );
    wp_enqueue_style( 'license-page', NETPEAK_SEO_PLUGIN_URL . 'assets/css/license-page.css' );
    wp_enqueue_script( 'tooltip', NETPEAK_SEO_PLUGIN_URL . 'assets/js/tooltip.js', array(), '3.0' );
    wp_enqueue_script( 'dependent-checkbox', NETPEAK_SEO_PLUGIN_URL . 'assets/js/dependent-checkbox.js', array(), '3.0' );
}
add_action( 'admin_enqueue_scripts', 'netpeak_assets_include_admin' );



