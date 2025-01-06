<?php
//Register
function netpeak_seo_widget_dashboard_license() {
    wp_add_dashboard_widget(
        'netpeak_widget_dashboard_license', 
        'Netpeak Tools | License Status',            
        'netpeak_render_widget_license',
    );
}
add_action('wp_dashboard_setup', 'netpeak_seo_widget_dashboard_license');

//Render
function netpeak_render_widget_license() {
    include NETPEAK_SEO_PLUGIN_DIR . 'inc/functions/license-check.php';
    ?>
    <div id="loader" style="display:none; text-align: center;">
        <img width="50" src="<?php echo NETPEAK_SEO_IMAGE . 'loading.gif'; ?>" alt="Loading...">
    </div>
    <div id="response"></div>
    <div class="row" style="gap: 20px; justify-content: space-between; display: flex;">
        <a class="button button-secondary" href="https://cdn.netpeak.dev/" target="_blank">Account</a>
        <a class="button button-secondary" href="https://cdn.netpeak.dev/support/" target="_blank">Support</a>
    </div>
    
    <?php
}


