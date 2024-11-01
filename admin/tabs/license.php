<?php
include NETPEAK_SEO_COMPONENTS_ADMIN . 'tab-header.php';
include NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/license-check.php';

// Function for saving email and password
function netpeak_save_license_credentials($email, $password) {
    update_option('netpeak_seo_license_email', $email);
    update_option('netpeak_seo_license_password', $password);
}
$license_status = 0;
$saved_email = get_option('netpeak_seo_license_email', ''); // Retrieving a saved email
$saved_password = get_option('netpeak_seo_license_password', ''); // Retrieve the saved password
?>

<h2 class="license-title"><?php _e('License Verification', 'netpeak-seo'); ?></h2>

<div id="loader" style="display:none; text-align: center;">
    <img width="50" src="<?php echo NETPEAK_SEO_IMAGE . 'loading.gif'; ?>" alt="Loading...">
</div>
<div id="response"></div>

<form id="auth-form" style="display:none;">
    <input type="email" name="email" placeholder="<?php esc_attr_e('Enter your email', 'netpeak-seo'); ?>" required value="<?php echo esc_attr($saved_email); ?>">
    <input type="password" name="password" placeholder="<?php esc_attr_e('Enter your password', 'netpeak-seo'); ?>" required autocomplete="current-password" value="<?php echo esc_attr($saved_password); ?>">
    <button type="button" id="auth-submit"><?php esc_attr_e('Login', 'netpeak-seo'); ?></button>
</form>

<!-- Form for entering the licence key -->
<form id="license-form" style="display:none;">
    <input type="text" name="license-key" placeholder="<?php esc_attr_e('Enter license key', 'netpeak-seo'); ?>" required>
    <button type="button" id="license-submit"><?php esc_attr_e('Save License Key', 'netpeak-seo'); ?></button>
</form>

