<?php
include NETPEAK_SEO_COMPONENTS_ADMIN . 'tab-header.php';
include NETPEAK_SEO_PLUGIN_DIR . 'assets/js/license-switch.php';
?>
<!--Notification -->
<?php if (get_option('netpeak_smtp_enabled') != 1) : ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _ex('You have not enabled the SMTP module. Please enable the "Enable STMP module" option', 'Mail Page', 'netpeak-seo'); ?>.</p>
    </div>
<?php endif; ?>

<div style="display:flex; gap:20px" class="licensed-feature">
    <div class="wrap">
        <h1><?php _e('SMTP Settings', 'netpeak-seo'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('netpeak_smtp_settings');
            do_settings_sections('netpeak_smtp_settings');
            ?>
            <table class="form-table">
                <!-- Global -->
                <tr valign="top">
                    <th><?php _e('Enable SMTP module', 'netpeak-seo'); ?></th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" class="global-switch" name="netpeak_smtp_enabled" value="1" 
                                <?php checked(1, get_option('netpeak_smtp_enabled', 0)); ?> />
                            <span class="slider"></span>
                        </label>
                    </td>
                </tr>
                <!-- Authentication -->
                <tr valign="top">
                    <th><?php _e('Authentication', 'netpeak-seo'); ?></th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" class="dependent-checkbox" name="netpeak_smtp_authentication" value="1" 
                                <?php checked(1, get_option('netpeak_smtp_authentication', 1)); ?> />
                            <span class="slider"></span>
                        </label>
                        <div class="tooltip" style="margin-left:20px;">
                            <span class="tooltip-icon">?</span>
                            <div class="tooltip-content">
                                <p><?php _e('Enable or disable SMTP authentication. Recommended to be enabled.', 'netpeak-seo'); ?></p>
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- Auto TLS -->
                <tr valign="top">
                    <th><?php _e('Auto TLS', 'netpeak-seo'); ?></th>
                    <td>
                        <label class="switch">
                            <input type="checkbox" class="dependent-checkbox" name="netpeak_smtp_auto_tls" value="1" 
                                <?php checked(1, get_option('netpeak_smtp_auto_tls', 1)); ?> />
                            <span class="slider"></span>
                        </label>
                        <div class="tooltip" style="margin-left:20px;">
                            <span class="tooltip-icon">?</span>
                            <div class="tooltip-content">
                                <p><?php _e('Automatically use TLS encryption if supported by the SMTP server.', 'netpeak-seo'); ?></p>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="netpeak_smtp_host"><?php _e('SMTP Host', 'netpeak-seo'); ?></label></th>
                    <td><input name="netpeak_smtp_host" id="smtp_host" value="<?php echo esc_attr(get_option('netpeak_smtp_host', '')); ?>" type="text" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="netpeak_smtp_port"><?php _e('SMTP Port', 'netpeak-seo'); ?></label></th>
                    <td><input name="netpeak_smtp_port" id="smtp_port" value="<?php echo esc_attr(get_option('netpeak_smtp_port', 587)); ?>" type="number" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="netpeak_smtp_username"><?php _e('SMTP Username', 'netpeak-seo'); ?></label></th>
                    <td><input name="netpeak_smtp_username" id="smtp_username" value="<?php echo esc_attr(get_option('netpeak_smtp_username', '')); ?>" type="text" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="netpeak_smtp_password"><?php _e('SMTP Password', 'netpeak-seo'); ?></label></th>
                    <td><input name="netpeak_smtp_password" id="smtp_password" value="<?php echo esc_attr(get_option('netpeak_smtp_password', '')); ?>" type="password" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="netpeak_smtp_from"><?php _e('From Email', 'netpeak-seo'); ?></label></th>
                    <td><input name="netpeak_smtp_from" id="smtp_from" value="<?php echo esc_attr(get_option('netpeak_smtp_from', '')); ?>" type="email" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="netpeak_smtp_from_name"><?php _e('From Name', 'netpeak-seo'); ?></label></th>
                    <td><input name="netpeak_smtp_from_name" id="smtp_from_name" value="<?php echo esc_attr(get_option('netpeak_smtp_from_name', '')); ?>" type="text" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="netpeak_smtp_encryption"><?php _e('Encryption', 'netpeak-seo'); ?></label></th>
                    <td>
                        <select name="netpeak_smtp_encryption" id="smtp_encryption">
                            <option value="tls" <?php selected(get_option('netpeak_smtp_encryption', 'tls'), 'tls'); ?>>TLS</option>
                            <option value="ssl" <?php selected(get_option('netpeak_smtp_encryption', 'tls'), 'ssl'); ?>>SSL</option>
                            <option value="none" <?php selected(get_option('netpeak_smtp_encryption', 'tls'), 'none'); ?>>None</option>
                        </select>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <?php submit_button(__('Save Settings', 'netpeak-seo')); ?>
            </p>
        </form>
    </div>


    <div class="mail-settings mail-settings--test-email">
        <h3><?php _e( 'Test Message', 'netpeak-seo' ); ?></h3>
        <form method="post" action="">
            <?php wp_nonce_field('netpeak_send_test_email_nonce'); ?>
            <div class="mail-settings__form-group">
                <input name="test_email" id="test_email" type="email" class="small-text" placeholder="example@example.com" required>
            </div>
            <div class="mail-settings__form-group">
                <input type="submit" name="submit_test_email" class="button button-primary" value="<?php _e( 'Send Test Email', 'netpeak-seo' );?>">
            </div>
        </form>
    </div>
</div>

<div class="mail-settings">
    <h3><?php _e('SMTP Settings Guide', 'netpeak-seo'); ?></h3>

    <div class="mail-settings__section">
        <h4><?php _e('Authentication', 'netpeak-seo'); ?></h4>
        <p><?php _e('Enable this option if your SMTP server requires authentication. Typically, most SMTP servers do require authentication, so it is recommended to leave this enabled.', 'netpeak-seo'); ?></p>
    </div>

    <div class="mail-settings__section">
        <h4><?php _e('Auto TLS', 'netpeak-seo'); ?></h4>
        <p><?php _e('Automatically use TLS encryption if it is supported by the SMTP server. If your server supports TLS, enabling this option will enhance the security of the SMTP connection.', 'netpeak-seo'); ?></p>
    </div>

    <div class="mail-settings__section">
        <h4><?php _e('SMTP Host', 'netpeak-seo'); ?></h4>
        <p><?php _e('This is the address of your SMTP server. Common SMTP hosts include smtp.gmail.com for Gmail, smtp.mail.yahoo.com for Yahoo, or your mail server\'s domain.', 'netpeak-seo'); ?></p>
    </div>

    <div class="mail-settings__section">
        <h4><?php _e('SMTP Port', 'netpeak-seo'); ?></h4>
        <p><?php _e('The port used to connect to your SMTP server. Common SMTP ports are:', 'netpeak-seo'); ?></p>
        <ul>
            <li><?php _e('587 for TLS', 'netpeak-seo'); ?></li>
            <li><?php _e('465 for SSL', 'netpeak-seo'); ?></li>
            <li><?php _e('25 for non-encrypted connections', 'netpeak-seo'); ?></li>
        </ul>
    </div>

    <div class="mail-settings__section">
        <h4><?php _e('SMTP Username', 'netpeak-seo'); ?></h4>
        <p><?php _e('The username used to authenticate with the SMTP server. This is usually your email address.', 'netpeak-seo'); ?></p>
    </div>

    <div class="mail-settings__section">
        <h4><?php _e('SMTP Password', 'netpeak-seo'); ?></h4>
        <p><?php _e('The password used to authenticate with the SMTP server. Ensure you enter this correctly to avoid authentication errors.', 'netpeak-seo'); ?></p>
    </div>

    <div class="mail-settings__section">
        <h4><?php _e('From Email', 'netpeak-seo'); ?></h4>
        <p><?php _e('This is the email address that will appear as the sender of your emails. It is recommended to use an email address that belongs to the same domain as your SMTP server to prevent being flagged as spam.', 'netpeak-seo'); ?></p>
    </div>

    <div class="mail-settings__section">
        <h4><?php _e('From Name', 'netpeak-seo'); ?></h4>
        <p><?php _e('The name that will be displayed as the sender of your emails. For example, you might want to use your company or personal name.', 'netpeak-seo'); ?></p>
    </div>

    <div class="mail-settings__section">
        <h4><?php _e('Encryption', 'netpeak-seo'); ?></h4>
        <p><?php _e('Choose the type of encryption for your SMTP connection:', 'netpeak-seo'); ?></p>
        <ul>
            <li><?php _e('TLS: This is a secure encryption protocol used with port 587.', 'netpeak-seo'); ?></li>
            <li><?php _e('SSL: Use this option if your SMTP server supports SSL encryption, typically used with port 465.', 'netpeak-seo'); ?></li>
            <li><?php _e('None: Choose this if your SMTP server does not require encryption (not recommended for security reasons).', 'netpeak-seo'); ?></li>
        </ul>
    </div>
</div>
