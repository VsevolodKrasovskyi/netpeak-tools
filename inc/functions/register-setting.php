<?php
function netpeak_register_settings() {
    #Alt&Title
    register_setting('netpeak_seo_alt_title_settings','netpeak_seo_alt_title_enabled');
    register_setting('netpeak_seo_alt_title_settings','netpeak_seo_alt_title_generate_elementor');
    register_setting('netpeak_seo_alt_title_settings','netpeak_seo_alt_title_auto_enabled');
    register_setting('netpeak_seo_alt_title_settings','netpeak_seo_alt_title_suffix');
    #HTML Sitemap
    register_setting( 'netpeak_seo_sitemap_settings', 'netpeak_seo_sitemap_enabled' );
    register_setting( 'netpeak_seo_sitemap_settings', 'netpeak_seo_sitemap_title' );
    register_setting( 'netpeak_seo_sitemap_settings', 'netpeak_seo_sitemap_post_types', array(
        'type' => 'array',
        'sanitize_callback' => 'netpeak_sanitize_array',
        'default' => array(),
    ));
    register_setting('netpeak_seo_sitemap_settings', 'netpeak_seo_sitemap_exclude_posts', array(
        'type' => 'array',
        'sanitize_callback' => 'netpeak_sanitize_array_exclude_posts',
        'default' => array(),
    ));
    #Redirect
    register_setting( 'netpeak_seo_redirect_settings', 'netpeak_seo_redirect_enable' );
    register_setting( 'netpeak_seo_redirect_settings', 'netpeak_seo_redirect_uppercase_lowercase' );
    register_setting( 'netpeak_seo_redirect_settings', 'netpeak_seo_no_redirect_woocommerce_out' );
    register_setting( 'netpeak_seo_redirect_settings', 'netpeak_seo_add_slash' );
    register_setting( 'netpeak_seo_redirect_settings', 'netpeak_seo_multislash' );
    #Schema
    register_setting( 'netpeak_seo_schema_ld_json', 'netpeak_seo_schema_organization_and_person' );
    #License
    register_setting( 'netpeak_seo_license', 'netpeak_seo_license_email' );
    register_setting( 'netpeak_seo_license', 'netpeak_seo_license_password' );
    register_setting( 'netpeak_seo_license', 'netpeak_seo_license_auth_token' );
    register_setting( 'netpeak_seo_license', 'netpeak_seo_license_key' );
    #Mail
    register_setting('netpeak_smtp_settings', 'netpeak_smtp_enabled');
    register_setting('netpeak_smtp_settings', 'netpeak_smtp_host');
    register_setting('netpeak_smtp_settings', 'netpeak_smtp_port');
    register_setting('netpeak_smtp_settings', 'netpeak_smtp_authentication');
    register_setting('netpeak_smtp_settings', 'netpeak_smtp_auto_tls');
    register_setting('netpeak_smtp_settings', 'netpeak_smtp_username');
    register_setting('netpeak_smtp_settings', 'netpeak_smtp_password');
    register_setting('netpeak_smtp_settings', 'netpeak_smtp_from');
    register_setting('netpeak_smtp_settings', 'netpeak_smtp_from_name');
    register_setting('netpeak_smtp_settings', 'netpeak_smtp_encryption');
    register_setting('netpeak_smtp_settings', 'netpeak_smtp_test_email');
}
add_action('admin_init', 'netpeak_register_settings');


function netpeak_sanitize_array($input) {
    return is_array($input) ? array_map('sanitize_text_field', $input) : array();
}

function netpeak_sanitize_array_exclude_posts($input) {
    if (is_array($input)) {
        return array_map('intval', $input); 
    }
    $input = explode(',', $input); 
    return array_map('intval', $input);
}
