<?php
wp_enqueue_style( 'schemas-tab', NETPEAK_SEO_PLUGIN_URL . 'assets/css/schemas-tab.css' );
?>

<div class="schema-structure-wrapper">
    <!-- Side navigation -->
    <div class="schema-sidebar">
        <a href="?page=netpeak-tools&tab=schema-structure" data-tab="" class="schema-tab schema-tab-active">
            <?php _e('Introduction', 'netpeak-seo'); ?>
        </a>
        <a href="?page=netpeak-tools&tab=schema-structure" data-tab="organization-and-person" class="schema-tab">
            <?php _e('Organization & Person', 'netpeak-seo'); ?>
        </a>
    </div>

    <!-- Tab content -->
    <div class="schema-content">
        <div id="loader-schema" style="display:none; text-align: center; margin: 20px 0;">
            <img width="50" src="<?php echo NETPEAK_SEO_IMAGE . 'loading.gif'; ?>" alt="Loading...">
        </div>
        <div id="schema-response-content">
            <?php include NETPEAK_SEO_COMPONENTS_ADMIN . 'schemas/intro.php'; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.schema-tab');
    const loader = document.getElementById('loader-schema');
    const responseContent = document.getElementById('schema-response-content');

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function (e) {
            if (this.getAttribute('type') === 'submit') return true;
            e.preventDefault();

            tabs.forEach(t => t.classList.remove('schema-tab-active'));
            this.classList.add('schema-tab-active');

            const tabValue = this.getAttribute('data-tab');

            loader.style.display = 'block';
            responseContent.style.display = 'none';

            fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'load_schema_tab',
                    'schema-tab': tabValue
                })
            })
            .then(response => response.text())
            .then(html => {
                loader.style.display = 'none';
                responseContent.innerHTML = html;
                responseContent.style.display = 'block';
            })
            .catch(error => {
                loader.style.display = 'none';
                responseContent.innerHTML = '<p>Error loading content.</p>';
                responseContent.style.display = 'block';
                console.error('Error:', error);
            });
        });
    });
});
</script>


