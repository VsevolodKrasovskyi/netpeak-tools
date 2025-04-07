<?php

namespace NetpeakTools\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function netpeak_seo_add_admin_menu() {
    add_submenu_page(
        '__return_false',
        __( 'Netpeak Tools', 'netpeak-seo' ),              
        __( 'Netpeak Tools', 'netpeak-seo' ),              
        'manage_options',                                
        'netpeak-tools',                              
        [Menu::class, 'render_logs_page'],                 
        '__return_false'
    );
}
add_action( 'admin_menu', __NAMESPACE__ . '\\netpeak_seo_add_admin_menu' );

class Menu
{
    private static function tabs($active_tab)
    {
        $tabs = [
            'html-maps' => 'HML Maps',
            'redirects' => 'Redirects',
            'mail-settings' => 'Mail Settings',
            'schema-structure' => 'Schema & Structure',
            
        ];
        
        $output = '';
        foreach ($tabs as $tab => $label) {
            $is_active = $active_tab === $tab ? 'netpeak-nav-tab-active' : '';
            $output .= '<a href="?page=netpeak-tools&tab=' . esc_attr($tab) . '" class="netpeak-nav-tab ' . esc_attr($is_active) . '">' . esc_html($label) . '</a>';
        }
        return $output;
    }
    public static function render_logs_page()
    {
        $active_tab = sanitize_text_field($_GET['tab'] ?? 'html-maps');

        echo '<div class="wrap">';
        echo '<h1 class="netpeak-settings-title">Netpeak Tools</h1>';
        echo '<div class="netpeak-nav-tab-wrapper">';
        echo self::tabs($active_tab);
        echo '</div>';

        echo '<div class="netpeak-tab-content-wrapper">';
        self::render_tab_content($active_tab);
        echo '</div>';
        echo '</div>';
    }

    private static function render_tab_content($active_tab)
    {
        if ($active_tab === 'html-maps') {
            include NETPEAK_SEO_PLUGIN_DIR . 'admin/tabs/sitemap-html.php';
        } elseif ($active_tab === 'redirects') {
            include NETPEAK_SEO_PLUGIN_DIR . 'admin/tabs/redirect.php';
        } elseif ($active_tab === 'mail-settings') {
            include NETPEAK_SEO_PLUGIN_DIR . 'admin/tabs/mail.php';
        } elseif ($active_tab === 'schema-structure') {
            include NETPEAK_SEO_PLUGIN_DIR . 'admin/tabs/schemas.php';
        }
    }

}





