<?php
/*
* Include Style
*/
function include_style_sitemap()
{
    wp_enqueue_style( 'netpeak-sitemap-style', NETPEAK_SEO_PLUGIN_URL . 'assets/css/sitemap.css' );
}
add_action( 'wp_enqueue_scripts', 'include_style_sitemap' );



function generate_html_sitemap() {

    $output = '<div class="sitemap-container">';
    $output .= '<ul>';

    // Get the selected posttypes from the options
    $selected_post_types = get_option('netpeak_seo_sitemap_post_types', array());
    // Get an array of publication IDs for the exception (it should already be an array)
    $exclude_post_ids = get_option('netpeak_seo_sitemap_exclude_posts', array());
    //Exclude the current page as well
    $current_page = get_the_ID();
    $exclude_post_ids[] = $current_page;
    
    if (empty($selected_post_types)) {
        return '<p>No posttapes are selected for display.</p>';
    }
    foreach ($selected_post_types as $post_type) {
        $args = array(
            'post_type' => $post_type,
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post__not_in' => $exclude_post_ids 
        );
        $posts = get_posts($args);

        if (!empty($posts)) {
            $output .= '<div class="sitemap-column">';
            $output .= '<h2>' . esc_html(get_post_type_object($post_type)->label) . '</h2><ul>';
            foreach ($posts as $post) {
                $output .= '<li><a href="' . get_permalink($post->ID) . '">' . get_the_title($post->ID) . '</a></li>';
            }
            $output .= '</ul>';
            $output .= '</div>';
        }
    }
    $output .= '</div>';

    return $output;
}

function netpeak_seo_sitemap_shortcode() {
    return generate_html_sitemap();
}
add_shortcode('html_sitemap', 'netpeak_seo_sitemap_shortcode');

function create_html_sitemap_page() {
    $page_title = get_option('netpeak_seo_sitemap_title', 'Карта сайта');
    $existing_page = get_page_by_title($page_title);

    if (is_null($existing_page)) {
        $page_content = '[html_sitemap]'; 
        $new_page = array(
            'post_title'   => $page_title,
            'post_content' => $page_content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => 1, 
        );

        wp_insert_post($new_page);
    }
}

register_activation_hook(__FILE__, 'create_html_sitemap_page');

function handle_create_html_sitemap_page() {
    if (isset($_POST['create_html_sitemap'])) {
        create_html_sitemap_page();
        wp_redirect(admin_url('edit.php?post_type=page'));
        exit;
    }
}
add_action('admin_init', 'handle_create_html_sitemap_page');

