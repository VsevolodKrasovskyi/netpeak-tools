<?php

if (!session_id()) {
    session_start();
}

$currentUrl = home_url(add_query_arg(null, null));

// Check if global redirect is enabled

    
// WooCommerce checkout exception
if (get_option('netpeak_seo_no_redirect_woocommerce_out') == 1) {
    add_action('woocommerce_before_checkout_form', 'set_no_redirect_flag_for_checkout');
    function set_no_redirect_flag_for_checkout() {
        if (!session_id()) {
            session_start(); 
        }
        $_SESSION['no_redirect_to_lowercase'] = true;
    }
}

// Remove multiple slashes
if (get_option('netpeak_seo_multislash') == 1) {
    $currentUrl = preg_replace('/\/+$/', '/', $currentUrl); // Remove extra slashes
}

// Add trailing slash if option enabled
if (get_option('netpeak_seo_add_slash') == 1 && substr($currentUrl, -1) !== '/') {
    $currentUrl .= '/';  
}

// Convert UPPERCASE to lowercase
if (get_option('netpeak_seo_redirect_uppercase_lowercase') == 1) {
    $modifiedUrl = strtolower($currentUrl);
    $lowercase_redirect_exceptions = array('wp-admin', 'api', 'login', 'register'); // Exceptions for lowercase conversion
    
    function has_exception($url, $exceptions) {
        foreach ($exceptions as $exception) {
            if (preg_match("/\b$exception\b/i", $url)) {
                return true;
            }
        }
        return false;
    }

    $hasException = has_exception($currentUrl, $lowercase_redirect_exceptions);
    $shouldRedirect = !$hasException && ($currentUrl !== $modifiedUrl) && (!isset($_SESSION['no_redirect_to_lowercase']) || $_SESSION['no_redirect_to_lowercase'] !== true);

    if ($shouldRedirect) {
        header('Location: ' . $modifiedUrl, true, 301);
        exit;
    }
}


