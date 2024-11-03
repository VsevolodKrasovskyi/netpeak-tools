<?php
include NETPEAK_SEO_COMPONENTS_ADMIN . 'tab-header.php';
wp_enqueue_style( 'schemas-tab', NETPEAK_SEO_PLUGIN_URL . 'assets/css/schemas-tab.css' );
?>
<div class="schema-structure-wrapper">
    <!-- Side navigation -->
    <div class="schema-sidebar">
        <a href="?page=netpeak-schema-and-structure" class="schema-tab <?php echo (!isset($_GET['tab'])) ? 'schema-tab-active' : ''; ?>">
            <?php _e('Introduction', 'netpeak-seo'); ?>
        </a>
        <a href="?page=netpeak-schema-and-structure&tab=organization-and-person" class="schema-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'organization-and-person') ? 'schema-tab-active' : ''; ?>">
            <?php _e('Organization & Person', 'netpeak-seo'); ?>
        </a>
    </div>

    <!-- Tab content -->
    <div class="schema-content">
        <?php
        $tab = isset($_GET['tab']) ? $_GET['tab'] : '';

        // Introductory page
        if ($tab == '') {
            echo '<div class="intro-content">';
            echo '<h3>' . __('Welcome to Schema & Structure', 'netpeak-seo') . '</h3>';
            echo '<p>' . __('Please select a schema type from the left menu to begin editing.', 'netpeak-seo') . '</p>';
            echo '</div>';
        } 
        //Organization & Person
        elseif ($tab == 'organization-and-person') {
            include NETPEAK_SEO_COMPONENTS_ADMIN . 'schemas/organization-and-person.php';
        } 
        
        ?>
    </div>
</div>
