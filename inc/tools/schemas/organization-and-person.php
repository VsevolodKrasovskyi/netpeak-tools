<?php

function output_schema_json_ld() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'netpeak_schema_ld_json';
    $schema_category = 'organization&person';

    // Getting data for the category 'organisation&person'
    $schema_row = $wpdb->get_row(
        $wpdb->prepare("SELECT schema_type, schema_data FROM {$table_name} WHERE schema_category = %s", $schema_category),
        ARRAY_A
    );

    if ($schema_row) {
        // Deserialise the data
        $schema = maybe_unserialize($schema_row['schema_data']);

        // Building JSON-LD structure
        $json_ld = array(
            '@context' => 'https://schema.org',
            '@type' => $schema_row['schema_type'],
            'name' => $schema['organization_name'],
            'legalName' => $schema['organization_legal_name'],
            'url' => $schema['organization_url'],
            'contactPoint' => array(
                '@type' => 'ContactPoint',
                'contactType' => $schema['contact_type'],
                'telephone' => $schema['contact_number'],
                'url' => $schema['contact_url']
            ),
            'logo' => $schema['organization_logo']
        );

        // Convert array to JSON
        $json_ld_output = json_encode($json_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        // Put it in the head
        echo '<script type="application/ld+json">' . $json_ld_output . '</script>';
    }
}

// Add to header on all pages
add_action('wp_head', 'output_schema_json_ld');

