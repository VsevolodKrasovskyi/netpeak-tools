<?php
include NETPEAK_SEO_COMPONENTS_ADMIN . 'tab-header.php';
?>
<h2><?php _e('Basic redirect','netpeak-seo');?></h2>

<?php if (get_option('netpeak_seo_redirect_enable') != 1) : ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _ex('To configure other options, switch on "Enable redirect GLOBAL" option', 'Sitemap page admin', 'netpeak-seo'); ?>.</p>
        </div>
    <?php endif; ?>

    <form method="post" action="options.php">
    <?php
        settings_fields('netpeak_seo_redirect_settings');
        do_settings_sections('netpeak_seo_redirect_settings');
    ?>
    <table class="form-table">
        <!-- Global Redirect Enable -->
        <tr valign="top">
            <th><?php _ex('Enable redirect GLOBAL', 'Sitemap page admin', 'netpeak-seo'); ?></th>
            <td>
                <label class="switch">
                    <input type="checkbox" class="global-switch" name="netpeak_seo_redirect_enable" value="1" <?php checked(1, get_option('netpeak_seo_redirect_enable'), true); ?> />
                    <span class="slider"></span>
                </label>
            </td>
        </tr>

        <!-- UPPERCASE to lowercase redirect -->
        <tr valign="top">
            <th><?php _ex('Enable redirect UPPERCASE to lowercase', 'Sitemap page admin', 'netpeak-seo'); ?></th>
            <td>
                <label class="switch switch-disable">
                    <input type="checkbox" class="dependent-checkbox" name="netpeak_seo_redirect_uppercase_lowercase" value="1" <?php checked(1, get_option('netpeak_seo_redirect_uppercase_lowercase'), true); ?> />
                    <span class="slider"></span>
                </label>
            </td>
        </tr>
        <?php
        if (is_plugin_active('woocommerce/woocommerce.php')) : 
        ?>
        <!-- WooCommerce checkout exception -->
        <tr valign="top">
            <th><?php _ex('Enable WooCommerce checkout exception', 'Sitemap page admin', 'netpeak-seo'); ?></th>
            <td>
                <label class="switch switch-disable">
                    <input type="checkbox" class="dependent-checkbox" name="netpeak_seo_no_redirect_woocommerce_out" value="1" <?php checked(1, get_option('netpeak_seo_no_redirect_woocommerce_out'), true); ?> />
                    <span class="slider"></span>
                </label>
            </td>
        </tr>
        <?php endif; ?>

        <!-- Add Slash at the end of URL -->
        <tr valign="top">
            <th><?php _ex('Add trailing slash to URL', 'Sitemap page admin', 'netpeak-seo'); ?></th>
            <td>
                <label class="switch switch-disable">
                    <input type="checkbox" class="dependent-checkbox" name="netpeak_seo_add_slash" value="1" <?php checked(1, get_option('netpeak_seo_add_slash'), true); ?> />
                    <span class="slider"></span>
                </label>
            </td>
        </tr>

        <!-- Remove multiple slashes -->
        <tr valign="top">
            <th><?php _ex('Remove multiple slashes in URL', 'Sitemap page admin', 'netpeak-seo'); ?></th>
            <td>
                <label class="switch switch-disable">
                    <input type="checkbox" class="dependent-checkbox" name="netpeak_seo_multislash" value="1" <?php checked(1, get_option('netpeak_seo_multislash'), true); ?> />
                    <span class="slider"></span>
                </label>
            </td>
        </tr>
    </table>

    <?php submit_button('Save settings'); ?>
</form>

