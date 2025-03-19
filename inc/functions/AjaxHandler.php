<?php
//SCHEMA & STRUCTURE
function load_schema_tab() {
    $tab = isset($_POST['tab']) ? sanitize_text_field($_POST['tab']) : '';
    

    if ($tab === '') {
        include NETPEAK_SEO_COMPONENTS_ADMIN . 'schemas/intro.php';
    } elseif ($tab === 'organization-and-person') {
        include NETPEAK_SEO_COMPONENTS_ADMIN . 'schemas/organization-and-person.php';
    } else {
        echo '<p>Invalid tab.</p>';
    }

    wp_die();
}
add_action( 'wp_ajax_load_schema_tab', 'load_schema_tab' );

/**
 * Save Organization and Person schema data.
 *
 * Handles the saving of the Organization and Person schema data when the user submits the form.
 *
 * @since 1.0.6
 */
function save_organization_and_person() {
    
    $schema_handler = new NetpeakTools\SchemaHandler();

    $category = 'organization&person';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_schema_data'])) {
        $data = [
            'schema_type' => sanitize_text_field($_POST['schema_type']),
            'organization_name' => sanitize_text_field($_POST['organization_name']),
            'organization_legal_name' => sanitize_text_field($_POST['organization_legal_name']),
            'organization_url' => esc_url_raw($_POST['organization_url']),
            'contact_type' => sanitize_text_field($_POST['contact_type']),
            'contact_number' => sanitize_text_field($_POST['contact_number']),
            'contact_url' => esc_url_raw($_POST['contact_url']),
            'organization_logo' => esc_url_raw($_POST['organization_logo']),
            'street_address' => sanitize_text_field($_POST['street_address']),
            'address_locality' => sanitize_text_field($_POST['address_locality']),
            'address_region' => sanitize_text_field($_POST['address_region']),
            'postal_code' => sanitize_text_field($_POST['postal_code']),
            'address_country' => sanitize_text_field($_POST['address_country']),
            'person_name' => sanitize_text_field($_POST['person_name']),
            'alternate_name' => sanitize_text_field($_POST['alternate_name']),
            'description' => sanitize_textarea_field($_POST['description']),
            'person_url' => esc_url_raw($_POST['person_url']),
            'job_title' => sanitize_text_field($_POST['job_title']),
            'affiliation' => sanitize_text_field($_POST['affiliation']),
            'birth_date' => sanitize_text_field($_POST['birth_date']),
            'contact_points' => sanitize_text_field($_POST['contact_points']),
            'person_image' => esc_url_raw($_POST['person_image']),
            'gender' => sanitize_text_field($_POST['gender']),
            'nationality' => sanitize_text_field($_POST['nationality']),
            'works_for' => sanitize_text_field($_POST['works_for']),
            'same_as' => esc_url_raw($_POST['same_as']),
        ];
        
        $schema_handler->saveSchema($category, $data);
        $redirect_url = wp_get_referer(); 
        if ($redirect_url) {
            wp_safe_redirect($redirect_url);
            exit; 
        }

    }
}

add_action('wp_ajax_save_organization_and_person', 'save_organization_and_person');

// AJAX handler for license tokens
add_action('wp_ajax_save_license_tokens', 'save_license_tokens');
add_action('wp_ajax_get_license_tokens', 'get_license_tokens');

function save_license_tokens() {
    $updated = false; 

    if (!empty($_POST['authToken']) && !empty($_POST['licenseKey'])) {
        update_option('netpeak_seo_license_auth_token', sanitize_text_field($_POST['authToken']));
        update_option('netpeak_seo_license_key', sanitize_text_field($_POST['licenseKey']));
        $updated = true;
    }

    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        update_option('netpeak_seo_license_email', sanitize_email($_POST['email']));
        update_option('netpeak_seo_license_password', sanitize_text_field($_POST['password']));
        $updated = true;
    }

    if ($updated) {
        wp_send_json_success('Data saved successfully.');
    } else {
        wp_send_json_error('No valid data provided.');
    }
}

function get_license_tokens() {
    $auth_token = get_option('netpeak_seo_license_auth_token');
    if ($auth_token) {
        wp_send_json_success([
            'token' => $auth_token
        ]);
    } else {
        wp_send_json_error(['message' => 'Token not found']);
    }
}

