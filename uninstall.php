<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

$options = array(
    // Alt & Title
    'netpeak_seo_alt_title_enabled',
    'netpeak_seo_alt_title_generate_elementor',
    'netpeak_seo_alt_title_auto_enabled',
    'netpeak_seo_alt_title_suffix',
    // HTML Sitemap
    'netpeak_seo_sitemap_enabled',
    'netpeak_seo_sitemap_title',
    'netpeak_seo_sitemap_post_types',
    'netpeak_seo_sitemap_exclude_posts',
    // Redirect
    'netpeak_seo_redirect_enable',
    'netpeak_seo_redirect_uppercase_lowercase',
    'netpeak_seo_no_redirect_woocommerce_out',
    'netpeak_seo_add_slash',
    'netpeak_seo_multislash',
    //Schemas
    'netpeak_seo_schema_organization_and_person',
    //License
    'netpeak_seo_license_email',
    'netpeak_seo_license_password',
    'netpeak_seo_license_auth_token',
    'netpeak_seo_license_key'
);
// Deleting all options from the database
foreach ($options as $option) {
    delete_option($option); // Remove the default options
    delete_site_option($option); // Remove options for multisites
}

function clear_cache_directory($dir) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $file_path = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($file_path)) {
                    clear_cache_directory($file_path);
                    rmdir($file_path);
                } else {
                    unlink($file_path);
                }
            }
        }
    }
}

$cache_dir = WP_CONTENT_DIR . '/netpeak_seo_cache';
clear_cache_directory($cache_dir);


global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%netpeak%'");

// $delete_table_schema = $wpdb->prefix . 'netpeak_schema_ld_json';
// if ($wpdb->get_var("SHOW TABLES LIKE '$delete_table_schema'") === $delete_table_schema) {
//     $wpdb->query("DROP TABLE IF EXISTS $delete_table_schema");
// }