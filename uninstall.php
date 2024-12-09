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
    'netpeak_seo_license_key',
    //Mail
    'netpeak_smtp_enabled',
    'netpeak_smtp_host',
    'netpeak_smtp_port',
    'netpeak_smtp_authentication',
    'netpeak_smtp_auto_tls',
    'netpeak_smtp_username',
    'netpeak_smtp_password',
    'netpeak_smtp_from',
    'netpeak_smtp_from_name',
    'netpeak_smtp_encryption',
    'netpeak_smtp_test_email'
);
// Deleting all options from the database
foreach ($options as $option) {
    delete_option($option); // Remove the default options
    delete_site_option($option); // Remove options for multisites
}
//Delete table
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '%netpeak%'");
//Clear cache directory
require_once plugin_dir_path(__FILE__) . 'inc/functions/CacheManager.php';
use NetpeakSEO\CacheManager;

$cacheManager = new CacheManager();
$cacheManager->clear();