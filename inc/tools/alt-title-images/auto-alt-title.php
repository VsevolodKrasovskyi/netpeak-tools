<?php
function modify_image_attributes($content) { 
    global $post;

    if (!$post) {
        return $content;
    }

    $current_page_title = esc_attr($post->post_title);
    $site_url = get_site_url();

    // Sufffix
    $title_suffix = esc_attr(get_option('netpeak_seo_alt_title_suffix', ''));
    $title_suffix = !empty($title_suffix) ? " - $title_suffix" : '';

    try {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $links_with_images = $xpath->query('//a[img]');

        foreach ($links_with_images as $link) {
            $href = $link->getAttribute('href');

            if (strpos($href, 'http') !== 0) {
                $href = rtrim($site_url, '/') . '/' . ltrim($href, '/');
            }

            $linked_post_id = url_to_postid($href);

            $alt_text = $current_page_title;
            $title_text = $alt_text . $title_suffix;

            if ($linked_post_id) {
                $linked_page_title = get_the_title($linked_post_id);
                if ($linked_page_title) {
                    $alt_text = esc_attr($linked_page_title);
                    $title_text = $alt_text . $title_suffix;
                }
            }

            $images = $link->getElementsByTagName('img');
            foreach ($images as $img) {
                // Skip image with class netpeak_image_title
                if ($img->hasAttribute('class') && strpos($img->getAttribute('class'), 'netpeak_image_title') !== false) {
                    continue;
                }

                $img->setAttribute('alt', $alt_text);
                $img->setAttribute('title', $title_text);
            }
        }

        $all_images = $xpath->query('//img[not(ancestor::a)]');
        foreach ($all_images as $img) {
            // Skip image with class netpeak_image_title
            if ($img->hasAttribute('class') && strpos($img->getAttribute('class'), 'netpeak_image_title') !== false) {
                continue;
            }

            $img->setAttribute('alt', $current_page_title);
            $img->setAttribute('title', $current_page_title . $title_suffix);
        }

        return $dom->saveHTML();

    } catch (Exception $e) {
        return $content;
    }
}

add_filter('the_content', 'modify_image_attributes', 20);
add_filter('widget_text', 'modify_image_attributes', 20);
add_filter('elementor/frontend/the_content', 'modify_image_attributes', 20);
