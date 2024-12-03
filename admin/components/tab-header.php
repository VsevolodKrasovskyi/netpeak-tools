<div class="wrap">
    <h1 class="netpeak-settings-title">
        <?php esc_html_e( 'Netpeak SEO Tools', 'netpeak-seo' );?>
        <img src="<?php echo esc_url( NETPEAK_SEO_IMAGE . 'netpeak-icon.png' ); ?>" alt="Netpeak.bg" width="35">
    </h1>

    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url( 'admin.php?page=netpeak-seo-alt-title' ); ?>" class="nav-tab netpeak-nav-tab <?php echo isset($_GET['page']) && $_GET['page'] == 'netpeak-seo-alt-title' ? 'netpeak-nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Alt&Title Image', 'netpeak-seo' ); ?>
        </a>
        <a href="<?php echo admin_url( 'admin.php?page=netpeak-seo-html-maps' ); ?>" class="nav-tab netpeak-nav-tab <?php echo isset($_GET['page']) && $_GET['page'] == 'netpeak-seo-html-maps' ? 'netpeak-nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'HTML Maps', 'netpeak-seo' ); ?>
        </a>
        <a href="<?php echo admin_url( 'admin.php?page=netpeak-seo-basic-redirect' ); ?>" class="nav-tab netpeak-nav-tab <?php echo isset($_GET['page']) && $_GET['page'] == 'netpeak-seo-basic-redirect' ? 'netpeak-nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Redirect', 'netpeak-seo' ); ?>
        </a>
        <a href="<?php echo admin_url( 'admin.php?page=netpeak-mail-setting' ); ?>" class="nav-tab netpeak-nav-tab <?php echo isset($_GET['page']) && $_GET['page'] == 'netpeak-mail-setting' ? 'netpeak-nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Mail Settings', 'netpeak-seo' ); ?>
        </a>
        <a href="<?php echo admin_url( 'admin.php?page=netpeak-schema-and-structure' ); ?>" class="nav-tab netpeak-nav-tab <?php echo isset($_GET['page']) && $_GET['page'] == 'netpeak-schema-and-structure' ? 'netpeak-nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'Schema & Structure', 'netpeak-seo' ); ?>
        </a>
    </h2>
</div>

