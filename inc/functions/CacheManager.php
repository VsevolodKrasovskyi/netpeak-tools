<?php

/*
* Cache
* @since 1.0.2
*/

namespace NetpeakSEO;

class CacheManager {
    private $cacheRootDir;

    public function __construct($cacheRootDir = null) {
        // The main directory for the cache (if not specified, defaults to wp-content/netpeak_seo_cache)
        $this->cacheRootDir = $cacheRootDir ?: WP_CONTENT_DIR . '/cache/netpeak_tools/cdn';
        if (!file_exists($this->cacheRootDir)) {
            wp_mkdir_p($this->cacheRootDir);
        }
    }

    /**
     * Method for setting data to cache
     *
     * @param string $key Cache key
     * @param mixed $data Data to be saved
     * @param int $expiration Cache lifetime in seconds
     * @param string $subDir Subdirectory for cache storage
     */

    // Method for setting data to cache
    public function set($key, $data, $expiration = HOUR_IN_SECONDS, $subDir = '') {
        $cacheDir = $this->cacheRootDir . '/' . $subDir;
        if (!file_exists($cacheDir)) {
            wp_mkdir_p($cacheDir);
        }
        
        $cacheFile = $cacheDir . '/' . md5($key) . '.cache';
        $cacheData = [
            'expires' => time() + $expiration,
            'data' => $data
        ];

        file_put_contents($cacheFile, serialize($cacheData));
    }

    // Method for retrieving data from the cache
    public function get($key, $subDir = '') {
        $cacheFile = $this->cacheRootDir . '/' . $subDir . '/' . md5($key) . '.cache';
        
        if (file_exists($cacheFile)) {
            $cacheData = unserialize(file_get_contents($cacheFile));
            if ($cacheData['expires'] >= time()) {
                return $cacheData['data'];
            } else {
                unlink($cacheFile);
            }
        }

        return null; 
    }

    // Method to delete cache by key
    public function delete($key, $subDir = '') {
        $cacheFile = $this->cacheRootDir . '/' . $subDir . '/' . md5($key) . '.cache';
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }

    // Method to clear the entire cache directory or subdirectory
    public function clear($subDir = '') {
        $cacheDir = $this->cacheRootDir . ($subDir ? '/' . $subDir : '');
        array_map('unlink', glob($cacheDir . '/*.cache'));
    }
}
