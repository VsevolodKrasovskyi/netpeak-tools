<?php
include NETPEAK_SEO_COMPONENTS_ADMIN . 'tab-header.php';

wp_enqueue_style( 'schemas-tab', NETPEAK_SEO_PLUGIN_URL . 'assets/css/schemas-tab.css' );
?>

<div class="schema-structure-wrapper">
    <!-- Side navigation -->
    <div class="schema-sidebar">
        <a href="?page=netpeak-schema-and-structure" data-tab="" class="schema-tab schema-tab-active">
            <?php _e('Introduction', 'netpeak-seo'); ?>
        </a>
        <a href="?page=netpeak-schema-and-structure&tab=organization-and-person" data-tab="organization-and-person" class="schema-tab">
            <?php _e('Organization & Person', 'netpeak-seo'); ?>
        </a>
    </div>

    <!-- Tab content -->
    <div class="schema-content">
        <div id="loader" style="display:none; text-align: center; margin: 20px 0;">
            <img width="50" src="<?php echo NETPEAK_SEO_IMAGE . 'loading.gif'; ?>" alt="Loading...">
        </div>
        <div id="response-content">
            <?php include NETPEAK_SEO_COMPONENTS_ADMIN . 'schemas/intro.php'; ?>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function ($) {
        $('.schema-tab').on('click', function (e) {
            if ($(this).attr('type') === 'submit') {
                return true; 
            }
            e.preventDefault();

            $('.schema-tab').removeClass('schema-tab-active');
            $(this).addClass('schema-tab-active');

            var tab = $(this).data('tab');
            var pageUrl = $(this).attr('href');

            history.pushState(null, '', pageUrl);

            $.ajax({
                url: ajaxurl, 
                type: 'POST',
                data: {
                    action: 'load_schema_tab',
                    tab: tab
                },
                beforeSend: function () {
                    $('#loader').show(); 
                    $('#response-content').hide(); 
                },
                success: function (response) {
                    $('#loader').hide(); 
                    $('#response-content').html(response).show();
                },
                error: function () {
                    $('#loader').hide();
                    $('#response-content').html('<p>Error loading content.</p>').show();
                }
            });
        });
    });
</script>
