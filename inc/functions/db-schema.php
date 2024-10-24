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
            schema_name varchar(255) NOT NULL,
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
        // Getting data from the form
        $schema_type = sanitize_text_field($_POST['schema_type']);
        $organization_name = sanitize_text_field($_POST['organization_name']);
        $organization_legal_name = sanitize_text_field($_POST['organization_legal_name']);
        $organization_url = esc_url_raw($_POST['organization_url']);
        $contact_type = sanitize_text_field($_POST['contact_type']);
        $contact_number = sanitize_text_field($_POST['contact_number']);
        $contact_url = esc_url_raw($_POST['contact_url']);
        $organization_logo = esc_url_raw($_POST['organization_logo']);

        // If no schema type is selected, do not save anything
        if (empty($schema_type)) {
            return;
        }

        // Data serialisation
        $schema_data = maybe_serialize(array(
            'schema_type' => $schema_type,
            'organization_name' => $organization_name,
            'organization_legal_name' => $organization_legal_name,
            'organization_url' => $organization_url,
            'contact_type' => $contact_type,
            'contact_number' => $contact_number,
            'contact_url' => $contact_url,
            'organization_logo' => $organization_logo,
        ));

        // Set a constant value for the category
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
                    'schema_name' => $schema_type,  // update schema type
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
                    'schema_name' => $schema_type,
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

    // Get schema data for the category "organisation&person" and schema
    $schema_data_row = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$table_name} WHERE schema_category = %s AND schema_name = %s",
        'organization&person',
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
        'organization_logo' => ''
    );
}
