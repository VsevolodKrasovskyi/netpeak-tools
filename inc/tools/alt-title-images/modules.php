<?php

if ( get_option( 'netpeak_seo_alt_title_generate_elementor' ) == 1 ) {
    require_once NETPEAK_SEO_PLUGIN_DIR . '/inc/tools/alt-title-images/elementor.php';
}

if ( get_option( 'netpeak_seo_alt_title_auto_enabled' ) == 1 ) {
    require_once NETPEAK_SEO_PLUGIN_DIR . '/inc/tools/alt-title-images/auto-alt-title.php';
}
