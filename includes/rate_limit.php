<?php
// includes/rate_limit.php
// Simple file-based rate limiting for authentication endpoints

/**
 * Check if the request should be rate limited
 * 
 * @param string $identifier IP address or email
 * @param int $maxAttempts Maximum attempts allowed
 * @param int $windowSeconds Time window in seconds
 * @return bool True if allowed, false if rate limited
 */
function checkRateLimit($identifier, $maxAttempts = 5, $windowSeconds = 900)
{
    // Create temp directory if needed
    $tempDir = sys_get_temp_dir() . '/gyan_rate_limit/';
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0755, true);
    }

    // Create rate limit file path (hash identifier for privacy)
    $file = $tempDir . 'rl_' . hash('sha256', $identifier) . '.json';

    $currentTime = time();
    $data = [
        'attempts' => 1,
        'window_start' => $currentTime,
        'last_attempt' => $currentTime
    ];

    // Read existing data if file exists
    if (file_exists($file)) {
        $existing = json_decode(file_get_contents($file), true);

        if ($existing && is_array($existing)) {
            // Check if within same time window
            if (($currentTime - $existing['window_start']) < $windowSeconds) {
                // Still in same window - check if limit exceeded
                if ($existing['attempts'] >= $maxAttempts) {
                    // Log the rate limit violation
                    error_log("Rate limit exceeded for identifier: " . substr($identifier, 0, 20) . "...");
                    return false; // Rate limit exceeded
                }

                // Increment attempts in same window
                $data = [
                    'attempts' => $existing['attempts'] + 1,
                    'window_start' => $existing['window_start'],
                    'last_attempt' => $currentTime
                ];
            }
            // else: window expired, start new window (use default $data)
        }
    }

    // Save updated data
    file_put_contents($file, json_encode($data), LOCK_EX);

    return true; // Allow request
}

/**
 * Reset rate limit for an identifier (e.g., on successful login)
 * 
 * @param string $identifier IP address or email
 */
function resetRateLimit($identifier)
{
    $tempDir = sys_get_temp_dir() . '/gyan_rate_limit/';
    $file = $tempDir . 'rl_' . hash('sha256', $identifier) . '.json';

    if (file_exists($file)) {
        unlink($file);
    }
}

/**
 * Get remaining attempts for an identifier
 * 
 * @param string $identifier IP address or email
 * @param int $maxAttempts Maximum attempts allowed
 * @param int $windowSeconds Time window
 * @return int Remaining attempts
 */
function getRemainingAttempts($identifier, $maxAttempts = 5, $windowSeconds = 900)
{
    $tempDir = sys_get_temp_dir() . '/gyan_rate_limit/';
    $file = $tempDir . 'rl_' . hash('sha256', $identifier) . '.json';

    if (!file_exists($file)) {
        return $maxAttempts;
    }

    $data = json_decode(file_get_contents($file), true);
    if (!$data) {
        return $maxAttempts;
    }

    $currentTime = time();

    // Check if window expired
    if (($currentTime - $data['window_start']) >= $windowSeconds) {
        return $maxAttempts; // Window expired, full attempts available
    }

    return max(0, $maxAttempts - $data['attempts']);
}
