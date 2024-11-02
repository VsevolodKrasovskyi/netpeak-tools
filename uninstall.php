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

