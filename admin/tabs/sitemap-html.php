<div class="wrap wrap-netpeak">

    <?php if (get_option('netpeak_seo_sitemap_enabled') != 1) : ?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _ex('To create the sitemap page, first enable the "Enable sitemap" option', 'Sitemap page admin', 'netpeak-seo'); ?>.</p>
        </div>
    <?php endif; ?>

    <form method="post" action="options.php">
        <?php
        settings_fields('netpeak_seo_sitemap_settings');
        do_settings_sections('netpeak_seo_sitemap_settings');

        // Get a list of all post types, excluding 'attachment'
        $post_types = get_post_types(array('public' => true), 'objects');
        $taxonomies = get_taxonomies(array( 'public' => true ), 'objects' );
        ?>
        <table class="form-table">
            <!-- Global Sitemap enable -->
            <tr valign="top">
                <th><?php _ex('Enable sitemap', 'Sitemap page admin', 'netpeak-seo'); ?></th>
                <td>
                    <label class="switch">
                        <input type="checkbox" name="netpeak_seo_sitemap_enabled" value="1" <?php checked(1, get_option('netpeak_seo_sitemap_enabled'), true); ?> />
                        <span class="slider"></span>
                    </label>
                </td>
            </tr>
            <!-- Name Sitemap -->
            <tr valign="top">
                <th><?php _ex('Sitemap title', 'Sitemap page admin', 'netpeak-seo'); ?></th>
                <td>
                    <input type="text" name="netpeak_seo_sitemap_title" value="<?php echo esc_attr(get_option('netpeak_seo_sitemap_title', 'Sitemap')); ?>" />
                </td>
            </tr>
            <!-- Select Posttype  -->
            <tr valign="top">
                <th><?php _ex('Select post types for the sitemap', 'Sitemap page admin', 'netpeak-seo'); ?></th>
                <td>
                    <?php foreach ($post_types as $post_type): ?>
                        <?php if ($post_type->name !== 'attachment'): ?>
                            <label>
                                <input type="checkbox" name="netpeak_seo_sitemap_post_types[]" value="<?php echo esc_attr($post_type->name); ?>"
                                <?php if (is_array(get_option('netpeak_seo_sitemap_post_types'))) checked(in_array($post_type->name, get_option('netpeak_seo_sitemap_post_types'))); ?> />
                                <?php echo esc_html($post_type->label); ?>
                            </label><br/>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </td>
            </tr>
            <!-- Exclude page by ID -->
            <tr valign="top">
                <th><?php _ex('Exclude posts by ID', 'Sitemap page admin', 'netpeak-seo'); ?></th>
                <td>
                    <input style="width: 350px;" type="text" id="post_id_input" name="netpeak_seo_sitemap_exclude_posts" placeholder="<?php _ex('Enter page IDs separated by commas...','netpeak-seo'); ?>"
                        value="<?php echo esc_attr(implode(',', get_option('netpeak_seo_sitemap_exclude_posts', array()))); ?>" />
                    <div class="tooltip" style="margin-left:20px;">
                        <span class="tooltip-icon">?</span>
                        <div class="tooltip-content">
                            <img class="tooltip-image" src="<?php echo esc_url(NETPEAK_SEO_PLUGIN_URL . 'assets/img/post_id.png'); ?>"/>
                            <p><?php _e('Get the ID of the page you want to exclude from the sitemap', 'netpeak-seo'); ?></p>
                        </div>
                    </div>
                    <p class="description"><?php _ex('Specify the page IDs to exclude from the sitemap. Use commas to separate multiple IDs.', 'Sitemap page admin', 'netpeak-seo');?></p>
                    
                </td>
            </tr>
            <!-- Select Taxonomies -->
            <tr valign="top">
                <th><?php _ex('Select taxomonies for the sitemap', 'Sitemap page admin', 'netpeak-seo'); ?></th>
                <td>
                    <?php foreach ($taxonomies as $tax): ?>
                        <?php if ($tax->name !== 'attachment'): ?>
                            <label>
                                <input type="checkbox" name="netpeak_seo_sitemap_taxonomy[]" value="<?php echo esc_attr($tax->name); ?>"
                                <?php if (is_array(get_option('netpeak_seo_sitemap_taxonomy'))) checked(in_array($tax->name, get_option('netpeak_seo_sitemap_taxonomy'))); ?> />
                                <?php echo esc_html($tax->label); ?>
                            </label><br/>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </td>
            </tr>
            <!-- Exclude taxonomies by ID -->
            <tr valign="top">
                <th><?php _ex('Exclude taxonomies by ID', 'Sitemap page admin', 'netpeak-seo'); ?></th>
                <td>
                    <input style="width: 350px;" type="text" id="post_id_input" name="netpeak_seo_sitemap_exclude_taxonomies" placeholder="<?php _ex('Enter terms IDs separated by commas...','netpeak-seo'); ?>"
                        value="<?php echo esc_attr(implode(',', get_option('netpeak_seo_sitemap_exclude_taxonomies', array()))); ?>" />
                    <div class="tooltip" style="margin-left:20px;">
                        <span class="tooltip-icon">?</span>
                        <div class="tooltip-content">
                            <img class="tooltip-image" src="<?php echo esc_url(NETPEAK_SEO_PLUGIN_URL . 'assets/img/tax_id.png'); ?>"/>
                            <p><?php _e('Get the ID of the terms you want to exclude from the sitemap', 'netpeak-seo'); ?></p>
                        </div>
                    </div>
                    <p class="description"><?php _ex('Specify the terms IDs to exclude from the sitemap. Use commas to separate multiple IDs.', 'Sitemap page admin', 'netpeak-seo');?></p>
                    
                </td>
            </tr>
            <!-- Create Sitemap -->
            <tr valign="top">
                <th><?php _ex('Create sitemap', 'Sitemap page admin', 'netpeak-seo'); ?></th>
                <td>
                    <?php if (get_option('netpeak_seo_sitemap_enabled') == 1) : ?>
                        <input type="submit" name="create_html_sitemap" value="<?php _e('Create sitemap','netpeak-seo')?>" class="button button-secondary" />
                    <?php else : ?>
                        <p><?php _ex('Please enable the sitemap to create the page', 'Sitemap page admin', 'netpeak-seo'); ?></p>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <?php submit_button('Save settings'); ?>
    </form>
    <div class="shortcode-more">
        <p><?php _e('Do you want to customise?','netpeak-seo');?></p></br>
        <div class="shortcode-example">
            <code>[html_sitemap only="taxonomy"]</code><p><?php _e('Outputs all selected taxonomies','netpeak-seo');?></p>
        </div>
        <div class="shortcode-example">
            <code>[html_sitemap only="post"]</code><p><?php _e('Outputs all selected posttypes','netpeak-seo');?></p>
        </div>
    </div>
</div>
