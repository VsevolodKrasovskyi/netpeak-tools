<?php
include NETPEAK_SEO_COMPONENTS_ADMIN . 'tab-header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_schema_data'])) {
    save_schema_data(); 
}
?>
<h2><?php _e('Schema & Structure','netpeak-seo');?></h2>

<form method="post" action="options.php">
    <?php
    // Get data
    $data = get_schema_data(isset($_POST['schema_type']) ? $_POST['schema_type'] : 'Organization');
    settings_fields( 'netpeak_seo_schema_ld_json' );
    do_settings_sections( 'netpeak_seo_schema_organization_and_person' );

    //Inputs
    $schema_type = isset($data['schema_type']) ? $data['schema_type'] : '';
    $organization_name = isset($data['organization_name']) ? $data['organization_name'] : '';
    $organization_legal_name = isset($data['organization_legal_name']) ? $data['organization_legal_name'] : '';
    $organization_url = isset($data['organization_url']) ? $data['organization_url'] : '';
    $contact_type = isset($data['contact_type']) ? $data['contact_type'] : '';
    $contact_number = isset($data['contact_number']) ? $data['contact_number'] : '';
    $contact_url = isset($data['contact_url']) ? $data['contact_url'] : '';
    $organization_logo = isset($data['organization_logo']) ? $data['organization_logo'] : '';
    ?>
    <tr valign="top">
        <th><?php _e('Enable Schema Organization', 'netpeak-seo'); ?></th>
        <td>
            <label class="switch">
                <input type="checkbox" class="global-switch" name="netpeak_seo_schema_organization_and_person" value="1" <?php checked(1, get_option('netpeak_seo_schema_organization_and_person'), true); ?> />
                <span class="slider"></span>
            </label>
        </td>
    </tr>
    <table class="form-table">
        <!-- Data Type -->
        <tr>
            <th scope="row"><label for="schema_type">Data Type</label></th>
            <td>
                <select name="schema_type" id="schema_type">
                    <option value="">-- Select Data Type --</option>
                    <option value="Organization" <?php selected($schema_type, 'Organization'); ?>>Organization</option>
                    <option value="Person" <?php selected($schema_type, 'Person'); ?>>Person</option>
                </select>
            </td>
        </tr>
        <!-- Organization Name -->
        <tr>
            <th scope="row"><label for="organization_name">Organization Name</label></th>
            <td>
                <input type="text" name="organization_name" id="organization_name" value="<?php echo esc_attr($organization_name); ?>" class="regular-text" />
            </td>
        </tr>
        <!-- Organization Legal Name -->
        <tr>
            <th scope="row"><label for="organization_legal_name">Organization LegalName</label></th>
            <td>
                <input type="text" name="organization_legal_name" id="organization_legal_name" value="<?php echo esc_attr($organization_legal_name); ?>" class="regular-text" />
            </td>
        </tr>
        <!-- Organization URL -->
        <tr>
            <th scope="row"><label for="organization_url">Organization URL</label></th>
            <td>
                <input type="url" name="organization_url" id="organization_url" value="<?php echo esc_url($organization_url); ?>" class="regular-text" />
            </td>
        </tr>
        <!-- Contact Type -->
        <tr>
            <th scope="row"><label for="contact_type">Contact Type</label></th>
            <td>
                <input type="text" name="contact_type" id="contact_type" value="<?php echo esc_attr($contact_type); ?>" class="regular-text" />
            </td>
        </tr>
        <!-- Contact Number -->
        <tr>
            <th scope="row"><label for="contact_number">Contact Number</label></th>
            <td>
                <input type="text" name="contact_number" id="contact_number" value="<?php echo esc_attr($contact_number); ?>" class="regular-text" />
            </td>
        </tr>
        <!-- Contact URL -->
        <tr>
            <th scope="row"><label for="contact_url">Contact URL</label></th>
            <td>
                <input type="url" name="contact_url" id="contact_url" value="<?php echo esc_url($contact_url); ?>" class="regular-text" />
            </td>
        </tr>
        <!-- Organization Logo -->
        <tr>
            <th scope="row"><label for="organization_logo">Logo</label></th>
            <td>
                <input type="url" name="organization_logo" id="organization_logo" value="<?php echo esc_url($organization_logo); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <p class="submit">
        <input type="submit" name="submit_schema_data" id="submit_schema_data" class="button button-primary" value="<?php _e('Save Settings', 'netpeak-seo'); ?>">
    </p>
</form>
