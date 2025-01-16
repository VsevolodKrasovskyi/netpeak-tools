<?php

namespace NetpeakTools;
class CDN {
    private $cacheManager;
    private $licenseKey;
    private $currentDomain;

    /**
     * CDN constructor.
     *
     * @since 1.0.5
     *
     * @param string $cacheRootDir The root directory for caching.
     */
    public function __construct() {
        $this->cacheManager = new CacheManager(WP_CONTENT_DIR . '/cache/netpeak/tools');
        $this->licenseKey = get_option('netpeak_seo_license_key');
        $this->currentDomain = $_SERVER['HTTP_HOST'];
    }

    // Function for adding a message to admin_notices
    private function addAdminNotice($message, $type = 'error') {
        set_transient('netpeak_seo_admin_notice', ['message' => $message, 'type' => $type], 30);
    }

    // Hook to display the message
    public static function displayAdminNotice() {
        if ($notice = get_transient('netpeak_seo_admin_notice')) {
            $class = $notice['type'] === 'success' ? 'notice-success' : 'notice-error';
            echo "<div class='notice {$class} is-dismissible'><p>{$notice['message']}</p></div>";
            delete_transient('netpeak_seo_admin_notice');
        }
    }

    // Authentication and token retrieval function
    private function getCdnToken() {
        $email = get_option('netpeak_seo_license_email'); 
        $password = get_option('netpeak_seo_license_password'); 

        $response = wp_remote_post('https://cdn.netpeak.dev/api/login', [
            'body' => [
                'email' => $email,
                'password' => $password,
            ],
        ]);

        if (is_wp_error($response)) {
            $this->addAdminNotice(__('CDN authentication error:', 'netpeak-seo') . ' ' . $response->get_error_message());
            return false;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($data['success']) && $data['success']) {
            return $data['token']; // return token
        } else {
            $this->addAdminNotice(__('Failed to get the token:', 'netpeak-seo') . ' ' . ($data['message'] ?? __('Unknown error', 'netpeak-seo')));
            return false;
        }
    }

    // Function for downloading and executing a script from CDN
    public function load_cdn_script($scriptName) {
        if (!$this->licenseKey) {
            $this->addAdminNotice(__('License key is missing. Unable to load the script.', 'netpeak-seo'));
            $this->cacheManager->clear();
            return;
        }

        $cacheKey = 'netpeak_seo_cdn_script_' . md5($scriptName);
        $cachedData = $this->cacheManager->get($cacheKey);

        // If the script is in the cache, check its integrity and use
        if ($cachedData) {
            $cachedHash = $this->cacheManager->get($cacheKey . '_hash');
            $decryptedScript = openssl_decrypt($cachedData, 'AES-256-CBC', $this->licenseKey, 0, substr($this->licenseKey, 0, 16));

            if ($decryptedScript && hash('sha256', $decryptedScript) === $cachedHash) {
                eval('?>' . $decryptedScript);
                return;
            }
        }

        // If the script is not found in the cache, request it from the CDN
        $token = $this->getCdnToken();
        if (!$token) {
            $this->addAdminNotice(__('Authentication Error. Failed to get a token to load the script.', 'netpeak-seo'));
            return;
        }

        $cdnScriptUrl = "https://cdn.netpeak.dev/api/load-script/{$scriptName}";
        $response = wp_remote_post($cdnScriptUrl, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'body' => [
                'license_key' => $this->licenseKey,
                'domain' => $this->currentDomain,
            ],
        ]);

        if (is_wp_error($response)) {
            $this->addAdminNotice(__('Error loading script from CDN:', 'netpeak-seo') . ' ' . $response->get_error_message());
            $this->cacheManager->clear();
            return;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['success']) && $data['success']) {
            $scriptContentBase64 = $data['script'];
            $scriptContent = base64_decode($scriptContentBase64);

            $encryptedScript = openssl_encrypt($scriptContent, 'AES-256-CBC', $this->licenseKey, 0, substr($this->licenseKey, 0, 16));
            $hash = hash('sha256', $scriptContent);
            $this->cacheManager->set($cacheKey, $encryptedScript, HOUR_IN_SECONDS);
            $this->cacheManager->set($cacheKey . '_hash', $hash, HOUR_IN_SECONDS);

            eval('?>' . $scriptContent);
        } else {
            $this->addAdminNotice(__('Netpeak SEO Tools:', 'netpeak-seo') . ' ' . ($data['message'] ?? __('Unknown error', 'netpeak-seo')));
        }
    }
}

// Hook to display admin notices
add_action('admin_notices', [CDN::class, 'displayAdminNotice']);
