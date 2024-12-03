<?php
//Constructor table
function create_schema_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'netpeak_schema_ld_json';

    if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            schema_category varchar(255) NOT NULL,
            schema_type varchar(255) NOT NULL,
            schema_data text NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

function save_schema_data() {
    global $wpdb;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_schema_data'])) {
        
        if (isset($_POST['netpeak_seo_schema_organization_and_person'])) {
            update_option('netpeak_seo_schema_organization_and_person', 1);
        } else {
            update_option('netpeak_seo_schema_organization_and_person', 0);
        }
        // Get data from the form
        $schema_type = sanitize_text_field($_POST['schema_type']);
        // Organization fields
        $organization_name = sanitize_text_field($_POST['organization_name']);
        $organization_legal_name = sanitize_text_field($_POST['organization_legal_name']);
        $organization_url = esc_url_raw($_POST['organization_url']);
        $contact_type = sanitize_text_field($_POST['contact_type']);
        $contact_number = sanitize_text_field($_POST['contact_number']);
        $contact_url = esc_url_raw($_POST['contact_url']);
        $organization_logo = esc_url_raw($_POST['organization_logo']);
        $street_address = sanitize_text_field($_POST['street_address']);
        $address_locality = sanitize_text_field($_POST['address_locality']);
        $address_region = sanitize_text_field($_POST['address_region']);
        $postal_code = sanitize_text_field($_POST['postal_code']);
        $address_country = sanitize_text_field($_POST['address_country']);
        // Person fields
        $person_name = sanitize_text_field($_POST['person_name']);
        $alternate_name = sanitize_text_field($_POST['alternate_name']);
        $description = sanitize_textarea_field($_POST['description']);
        $person_url = esc_url_raw($_POST['person_url']);
        $job_title = sanitize_text_field($_POST['job_title']);
        $affiliation = sanitize_text_field($_POST['affiliation']);
        $birth_date = sanitize_text_field($_POST['birth_date']);
        $contact_points = sanitize_text_field($_POST['contact_points']);
        $person_image = esc_url_raw($_POST['person_image']);
        $gender = sanitize_text_field($_POST['gender']);
        $nationality = sanitize_text_field($_POST['nationality']);
        $works_for = sanitize_text_field($_POST['works_for']);
        $same_as = esc_url_raw($_POST['same_as']);

        // If no schema type is selected, do not save anything
        if (empty($schema_type)) {
            return;
        }
        // Data serialisation
        $schema_data = maybe_serialize(array(
            'schema_type' => $schema_type,
            //Organization
            'organization_name' => $organization_name,
            'organization_legal_name' => $organization_legal_name,
            'organization_url' => $organization_url,
            'contact_type' => $contact_type,
            'contact_number' => $contact_number,
            'contact_url' => $contact_url,
            'organization_logo' => $organization_logo,
            'street_address' => $street_address,
            'address_locality' => $address_locality,
            'address_region' => $address_region,
            'postal_code' => $postal_code,
            'address_country' => $address_country,
            // Person fields
            'person_name' => $person_name,
            'alternate_name' => $alternate_name,
            'description' => $description,
            'person_url' => $person_url,
            'job_title' => $job_title,
            'affiliation' => $affiliation,
            'birth_date' => $birth_date,
            'contact_points' => $contact_points,
            'person_image' => $person_image,
            'gender' => $gender,
            'nationality' => $nationality,
            'works_for' => $works_for,
            'same_as' => $same_as,
        ));
        // Category
        $schema_category = 'organization&person';
        // Table
        $table_name = $wpdb->prefix . 'netpeak_schema_ld_json';

        // Check if there is an entry for this category (ignoring the schema, as it may change)
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$table_name} WHERE schema_category = %s",
            $schema_category
        ));

        if ($existing) {
            // Updating an existing record
            $wpdb->update(
                $table_name,
                array(
                    'schema_type' => $schema_type,  // update schema type
                    'schema_data' => $schema_data   // update schema data
                ),
                array('id' => $existing)  // update by the ID of the found record
            );
        } else {
            // Insert a new entry if it doesn't exist yet
            $wpdb->insert(
                $table_name,
                array(
                    'schema_category' => $schema_category,
                    'schema_type' => $schema_type,
                    'schema_data' => $schema_data,
                )
            );
        }
    }
}
function get_schema_data($schema_type = 'Organization') {
    global $wpdb;    
    // Table in the db
    $table_name = $wpdb->prefix . 'netpeak_schema_ld_json';
    $schema_category = 'organization&person';
    // Get schema data for the category "organisation&person" and schema
    $schema_data_row = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$table_name} WHERE schema_category = %s AND schema_type = %s",
        $schema_category,
        $schema_type
    ), ARRAY_A);

    if ($schema_data_row) {
        return maybe_unserialize($schema_data_row['schema_data']);
    }

    // Return empty data if nothing is present
    return array(
        'schema_type' => '',
        'organization_name' => '',
        'organization_legal_name' => '',
        'organization_url' => '',
        'contact_type' => '',
        'contact_number' => '',
        'contact_url' => '',
        'organization_logo' => '',
        'street_address' => '',
        'address_locality' => '',
        'address_region' => '',
        'postal_code' => '',
        'address_country' => ''
    );

}