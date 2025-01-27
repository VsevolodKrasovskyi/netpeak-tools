<form id="schema-form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
    <?php
    $schema_handler = new NetpeakTools\SchemaHandler();
    $category = 'organization&person';
    
    $organization_data = $schema_handler->getSchema($category, 'Organization');
    $person_data = $schema_handler->getSchema($category, 'Person');
    
    // Initialization of variables
    $schema_type = $organization_data['schema_type'] ?? ($person_data['schema_type'] ?? '');
    $organization_name = $organization_data['organization_name'] ?? '';
    $organization_legal_name = $organization_data['organization_legal_name'] ?? '';
    $organization_url = $organization_data['organization_url'] ?? '';
    $contact_type = $organization_data['contact_type'] ?? '';
    $contact_number = $organization_data['contact_number'] ?? '';
    $contact_url = $organization_data['contact_url'] ?? '';
    $organization_logo = $organization_data['organization_logo'] ?? '';
    $street_address = $organization_data['street_address'] ?? '';
    $address_locality = $organization_data['address_locality'] ?? '';
    $address_region = $organization_data['address_region'] ?? '';
    $postal_code = $organization_data['postal_code'] ?? '';
    $address_country = $organization_data['address_country'] ?? '';
    $person_name = $person_data['person_name'] ?? '';
    $alternate_name = $person_data['alternate_name'] ?? '';
    $description = $person_data['description'] ?? '';
    $person_url = $person_data['person_url'] ?? '';
    $job_title = $person_data['job_title'] ?? '';
    $affiliation = $person_data['affiliation'] ?? '';
    $birth_date = $person_data['birth_date'] ?? '';
    $contact_points = $person_data['contact_points'] ?? '';
    $person_image = $person_data['person_image'] ?? '';
    $gender = $person_data['gender'] ?? '';
    $nationality = $person_data['nationality'] ?? '';
    $works_for = $person_data['works_for'] ?? '';
    $same_as = $person_data['same_as'] ?? '';
    ?>    

    <input type="hidden" name="action" value="save_organization_and_person">
    <div class="title-global-switch">
        <h2><?php _e('Enable Global ', 'netpeak-seo'); ?></h2>
        <div class="global-switch-button">
            <label class="switch">
                <input type="checkbox" class="global-switch" name="netpeak_seo_schema_organization_and_person" value="1" <?php checked(1, get_option('netpeak_seo_schema_organization_and_person'), true); ?> />
                <span class="slider"></span>
            </label>
        </div>
    </div>
    <table id="schema-table" class="form-table">
        <!-- Data Type -->
        <tr>
            <th scope="row"><label for="schema_type">Data Type</label></th>
            <td>
                <select name="schema_type" id="schema_type">
                    <option value="" disabled >-- Select Data Type --</option>
                    <option value="Organization" <?php selected($schema_type, 'Organization'); ?>>Organization</option>
                    <option value="Person" <?php selected($schema_type, 'Person'); ?>>Person</option>
                </select>
            </td>
        </tr>
    <!-- Organization Fields -->
    <tr class="organization-field">
            <th scope="row"><label for="organization_name">Organization Name</label></th>
            <td><input type="text" name="organization_name" id="organization_name" value="<?php echo esc_attr($organization_name); ?>" class="regular-text" /></td>
        </tr>
        <tr class="organization-field">
            <th scope="row"><label for="organization_legal_name">Organization Legal Name</label></th>
            <td><input type="text" name="organization_legal_name" id="organization_legal_name" value="<?php echo esc_attr($organization_legal_name); ?>" class="regular-text" /></td>
        </tr>
        <tr class="organization-field">
            <th scope="row"><label for="organization_url">Organization URL</label></th>
            <td><input type="url" name="organization_url" id="organization_url" value="<?php echo esc_url($organization_url); ?>" class="regular-text" /></td>
        </tr>
        <tr class="organization-field">
            <th scope="row"><label for="contact_type">Contact Type</label></th>
            <td><input type="text" name="contact_type" id="contact_type" value="<?php echo esc_attr($contact_type); ?>" class="regular-text" /></td>
        </tr>
        <tr class="organization-field">
            <th scope="row"><label for="contact_number">Contact Number</label></th>
            <td><input type="text" name="contact_number" id="contact_number" value="<?php echo esc_attr($contact_number); ?>" class="regular-text" /></td>
        </tr>
        <tr class="organization-field">
            <th scope="row"><label for="contact_url">Contact URL</label></th>
            <td><input type="url" name="contact_url" id="contact_url" value="<?php echo esc_url($contact_url); ?>" class="regular-text" /></td>
        </tr>
        <tr class="organization-field">
            <th scope="row"><label for="organization_logo">Logo</label></th>
            <td><input type="url" name="organization_logo" id="organization_logo" value="<?php echo esc_url($organization_logo); ?>" class="regular-text" /></td>
        </tr>
        <tr class="organization-field">
            <th scope="row"><label for="street_address">Street Address</label></th>
            <td><input type="text" name="street_address" id="street_address" value="<?php echo esc_attr($street_address); ?>" class="regular-text" /></td>
        </tr>
        <tr class="organization-field">
            <th scope="row"><label for="address_locality">City (Address Locality)</label></th>
            <td><input type="text" name="address_locality" id="address_locality" value="<?php echo esc_attr($address_locality); ?>" class="regular-text" /></td>
        </tr>
        <tr class="organization-field">
            <th scope="row"><label for="address_region">State/Region</label></th>
            <td><input type="text" name="address_region" id="address_region" value="<?php echo esc_attr($address_region); ?>" class="regular-text" /></td>
        </tr>
        <tr class="organization-field">
            <th scope="row"><label for="postal_code">Postal Code</label></th>
            <td><input type="text" name="postal_code" id="postal_code" value="<?php echo esc_attr($postal_code); ?>" class="regular-text" /></td>
        </tr>
        <tr class="organization-field">
            <th scope="row"><label for="address_country">Country</label></th>
            <td><input type="text" name="address_country" id="address_country" value="<?php echo esc_attr($address_country); ?>" class="regular-text" /></td>
        </tr>

        <!-- Person Fields -->
        <tr class="person-field">
            <th scope="row"><label for="person_name">Name</label></th>
            <td><input type="text" name="person_name" id="person_name" value="<?php echo esc_attr($person_name); ?>" class="regular-text" /></td>
        </tr>
        <tr class="person-field">
            <th scope="row"><label for="alternate_name">Alternate Name</label></th>
            <td><input type="text" name="alternate_name" id="alternate_name" value="<?php echo esc_attr($alternate_name); ?>" class="regular-text" /></td>
        </tr>
        <tr class="person-field">
            <th scope="row"><label for="description">Description</label></th>
            <td><textarea name="description" id="description" class="regular-text"><?php echo esc_textarea($description); ?></textarea></td>
        </tr>
        <tr class="person-field">
            <th scope="row"><label for="person_url">URL</label></th>
            <td><input type="url" name="person_url" id="person_url" value="<?php echo esc_url($person_url); ?>" class="regular-text" /></td>
        </tr>
        <tr class="person-field">
            <th scope="row"><label for="job_title">Job Title</label></th>
            <td><input type="text" name="job_title" id="job_title" value="<?php echo esc_attr($job_title); ?>" class="regular-text" /></td>
        </tr>
        <tr class="person-field">
            <th scope="row"><label for="affiliation">Affiliation</label></th>
            <td><input type="text" name="affiliation" id="affiliation" value="<?php echo esc_attr($affiliation); ?>" class="regular-text" /></td>
        </tr>
        <tr class="person-field">
            <th scope="row"><label for="birth_date">Birth Date</label></th>
            <td><input type="date" name="birth_date" id="birth_date" value="<?php echo esc_attr($birth_date); ?>" class="regular-text" /></td>
        </tr>
        <tr class="person-field">
            <th scope="row"><label for="contact_points">Contact Points</label></th>
            <td><input type="text" name="contact_points" id="contact_points" value="<?php echo esc_attr($contact_points); ?>" class="regular-text" /></td>
        </tr>
        <tr class="person-field">
            <th scope="row"><label for="person_image">Image</label></th>
            <td><input type="url" name="person_image" id="person_image" value="<?php echo esc_url($person_image); ?>" class="regular-text" /></td>
        </tr>
        <tr class="person-field">
            <th scope="row"><label for="gender">Gender</label></th>
            <td><input type="text" name="gender" id="gender" value="<?php echo esc_attr($gender); ?>" class="regular-text" /></td>
        </tr>
        <tr class="person-field">
            <th scope="row"><label for="nationality">Nationality</label></th>
            <td><input type="text" name="nationality" id="nationality" value="<?php echo esc_attr($nationality); ?>" class="regular-text" /></td>
        </tr>
        <tr class="person-field">
            <th scope="row"><label for="works_for">Works For</label></th>
            <td><input type="text" name="works_for" id="works_for" value="<?php echo esc_attr($works_for); ?>" class="regular-text" /></td>
        </tr>
        <tr class="person-field">
            <th scope="row"><label for="same_as">Same As (Social Links)</label></th>
            <td><input type="url" name="same_as" id="same_as" value="<?php echo esc_url($same_as); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <p class="submit">
        <input type="submit" name="submit_schema_data" id="submit_schema_data" class="button button-primary" value="<?php _e('Save Settings', 'netpeak-seo'); ?>">
    </p>
</form>
<script>
jQuery(document).ready(function($) {
    function toggleFields() {
        const schemaType = $('#schema_type').val();
        if (schemaType === 'Person') {
            $('.person-field').show();
            $('.organization-field').hide();
        } else if (schemaType === 'Organization') {
            $('.organization-field').show();
            $('.person-field').hide();
        }
    }

    $('#schema_type').change(function() {
        toggleFields();
    });

    // Run on page load
    toggleFields();
});
</script>
