<?php
//SCHEMA & STRUCTURE
function load_schema_tab() {
    $tab = isset($_POST['schema-tab']) ? sanitize_text_field($_POST['schema-tab']) : '';
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
