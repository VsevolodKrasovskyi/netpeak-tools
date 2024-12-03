<?php
include NETPEAK_SEO_COMPONENTS_ADMIN . 'tab-header.php';
require_once NETPEAK_SEO_PLUGIN_DIR . 'assets/js/license-switch.php';
?>
<div class="wrap wrap-netpeak">
    <form method="post" action="options.php">
        <?php
        settings_fields( 'netpeak_seo_alt_title_settings' );
        do_settings_sections( 'netpeak_seo_alt_title_settings' );
        ?>
        <table class="form-table">
            <!-- Global Atl&Title image enable -->
            <tr valign="top">
                <th><?php _ex('Enable Atl Title module', 'alt title page admin', 'netpeak-seo'); ?></th>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="global-switch" name="netpeak_seo_alt_title_enabled" value="1" <?php checked(1, get_option('netpeak_seo_alt_title_enabled'), true); ?> />
                        <span class="slider"></span>
                    </label>
                </td>
            </tr>
            <!-- Elementor integration -->
            <tr valign="top" class="licensed-feature">
                <th><?php _ex('Addon for Elementor', 'alt title page admin', 'netpeak-seo'); ?></th>
                <td>
                    <label class="switch ">
                        <input type="checkbox" class="dependent-checkbox" name="netpeak_seo_alt_title_generate_elementor" value="1" <?php checked(1, get_option('netpeak_seo_alt_title_generate_elementor'), true); ?> />
                        <span class="slider"></span>
                    </label>
                    <div class="tooltip" style="margin-left:20px;">
                    <span class="tooltip-icon">?</span>
                        <div class="tooltip-content">
                            <img src="<?php echo esc_url(NETPEAK_SEO_IMAGE .'elementor-field.gif');?>" alt="Описание с GIF"/>
                            <p><?php _ex('Adds the ability to set custom alt and title attributes for images in Elementor widgets, improving SEO and content accessibility','tooltip Elementor Alt Title','netpeak-seo');?></p>
                        </div>
                    </div>
                </td>
            </tr>
            <!-- Enable automatic Atl&Title -->
            <tr valign="top" class="licensed-feature">
                <th><?php _ex('Enable Auto Genetare Atl&Title', 'alt title page admin', 'netpeak-seo'); ?></th>
                <td>
                    <label class="switch">
                        <input type="checkbox" class="dependent-checkbox" name="netpeak_seo_alt_title_auto_enabled" value="1" <?php checked(1, get_option('netpeak_seo_alt_title_auto_enabled'), true); ?> />
                        <span class="slider"></span>
                    </label>
                </td>
            </tr>
            <!-- Title Suffix -->
            <tr valign="top">
                <th><?php _e('Title Suffix', 'netpeak-seo'); ?></th>
                <td>
                    <input type="text" id="title_suffix_input" name="netpeak_seo_alt_title_suffix" placeholder="<?php _ex('Enter title suffix...','netpeak-seo'); ?>"
                        value="<?php echo esc_attr(get_option('netpeak_seo_alt_title_suffix', get_bloginfo('name'))); ?>" />
                    <div class="tooltip" style="margin-left:20px;">
                    <span class="tooltip-icon">?</span>
                        <div class="tooltip-content">
                            <img src="<?php echo esc_url(NETPEAK_SEO_IMAGE .'custom_suffix.png');?>" alt="Описание с GIF"/>
                            <p><?php _ex('Adds a suffix to a title','Alt Title Custom Suffix','netpeak-seo');?></p>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <?php submit_button(_e('Save settings', 'netpeak-seo')); ?>
    </form>
</div>