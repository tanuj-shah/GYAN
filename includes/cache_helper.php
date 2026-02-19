<?php
/**
 * Simple file-based page caching for GYAN
 * Use for public pages with infrequent updates
 * 
 * Usage:
 * 1. At the top of your page (before any output):
 *    $cached = getCachedPage('homepage', 300);
 *    if ($cached !== false) {
 *        echo $cached;
 *        exit;
 *    }
 *    ob_start();
 * 
 * 2. At the bottom of your page:
 *    $output = ob_get_contents();
 *    saveCachedPage('homepage', $output);
 *    ob_end_flush();
 */

/**
 * Get cached page if it exists and is still valid
 * 
 * @param string $cacheKey Unique identifier for this page
 * @param int $ttl Time to live in seconds (default: 1 hour)
 * @return string|false Returns cached content or false if expired/not found
 */
function getCachedPage($cacheKey, $ttl = 3600)
{
    $cacheDir = __DIR__ . '/../cache/pages/';
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }

    $cacheFile = $cacheDir . md5($cacheKey) . '.html';

    // Check if cache file exists and is still valid
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        return file_get_contents($cacheFile);
    }

    return false;
}

/**
 * Save page content to cache
 * 
 * @param string $cacheKey Unique identifier for this page
 * @param string $content HTML content to cache
 * @return bool Success status
 */
function saveCachedPage($cacheKey, $content)
{
    $cacheDir = __DIR__ . '/../cache/pages/';
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }

    $cacheFile = $cacheDir . md5($cacheKey) . '.html';

    return file_put_contents($cacheFile, $content) !== false;
}

/**
 * Clear cache for specific key or all cached pages
 * 
 * @param string $pattern Pattern to match (default: '*' = all)
 * @return int Number of files deleted
 */
function clearCache($pattern = '*')
{
    $cacheDir = __DIR__ . '/../cache/pages/';
    if (!is_dir($cacheDir)) {
        return 0;
    }

    $files = glob($cacheDir . $pattern . '.html');
    $deleted = 0;

    foreach ($files as $file) {
        if (is_file($file) && unlink($file)) {
            $deleted++;
        }
    }

    return $deleted;
}

/**
 * Get cache statistics
 * 
 * @return array Cache stats (total files, total size)
 */
function getCacheStats()
{
    $cacheDir = __DIR__ . '/../cache/pages/';
    if (!is_dir($cacheDir)) {
        return ['files' => 0, 'size' => 0];
    }

    $files = glob($cacheDir . '*.html');
    $totalSize = 0;

    foreach ($files as $file) {
        if (is_file($file)) {
            $totalSize += filesize($file);
        }
    }

    return [
        'files' => count($files),
        'size' => $totalSize,
        'size_mb' => round($totalSize / 1048576, 2)
    ];
}
