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
        // Data deserialisation
        $schema_data = maybe_unserialize($schema_row['schema_data']);
        
        // Calling the appropriate function to output JSON-LD
        if ($schema_row['schema_type'] === 'Organization') {
            output_organization_json_ld($schema_data);
        } elseif ($schema_row['schema_type'] === 'Person') {
            output_person_json_ld($schema_data);
        }
    }
}

function output_organization_json_ld($schema) {
    $json_ld = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $schema['organization_name'],
        'legalName' => $schema['organization_legal_name'],
        'url' => $schema['organization_url'],
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'contactType' => $schema['contact_type'],
            'telephone' => $schema['contact_number'],
            'url' => $schema['contact_url']
        ),
        'logo' => $schema['organization_logo'],
        'address' => array(
            '@type' => 'PostalAddress',
            'streetAddress' => $schema['street_address'],
            'addressLocality' => $schema['address_locality'],
            'addressRegion' => $schema['address_region'],
            'postalCode' => $schema['postal_code'],
            'addressCountry' => $schema['address_country']
        )
    );

    // Convert array to JSON and output to head
    echo '<script type="application/ld+json">' . json_encode($json_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

function output_person_json_ld($schema) {
    $json_ld = array(
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $schema['person_name'],
        'alternateName' => $schema['alternate_name'],
        'description' => $schema['description'],
        'url' => $schema['person_url'],
        'jobTitle' => $schema['job_title'],
        'affiliation' => $schema['affiliation'],
        'birthDate' => $schema['birth_date'],
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'contactType' => $schema['contact_points']
        ),
        'image' => $schema['person_image'],
        'gender' => $schema['gender'],
        'nationality' => $schema['nationality'],
        'worksFor' => $schema['works_for'],
        'sameAs' => $schema['same_as']
    );

    // Convert array to JSON and output to head
    echo '<script type="application/ld+json">' . json_encode($json_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}

// Add to header on all pages
add_action('wp_head', 'output_schema_json_ld');
