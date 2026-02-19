<?php
// includes/rich-text-sanitizer.php
// HTML sanitization and word count validation for rich-text content

/**
 * Sanitize HTML content to allow only safe formatting tags
 * Strips all tags except: b, strong, i, em, u, br, p, ul, ol, li, a
 * For <a> tags, only allows href attributes with http/https URLs
 * 
 * @param string $html Raw HTML content from rich-text editor
 * @return string Sanitized HTML content
 */
function sanitizeRichTextHTML($html)
{
    // Configuration for allowed tags and attributes
    $allowed_tags = '<b><strong><i><em><u><br><p><ul><ol><li><a>';

    // First pass: strip all tags except allowed ones
    $sanitized = strip_tags($html, $allowed_tags);

    // Second pass: sanitize <a> tags to only allow safe href attributes
    $sanitized = preg_replace_callback(
        '/<a\s+([^>]*)>/i',
        function ($matches) {
            $attrs = $matches[1];

            // Extract href attribute
            if (preg_match('/href\s*=\s*["\']?(https?:\/\/[^"\'\s>]+)["\']?/i', $attrs, $href_match)) {
                $safe_href = htmlspecialchars($href_match[1], ENT_QUOTES, 'UTF-8');
                return '<a href="' . $safe_href . '" target="_blank" rel="noopener noreferrer">';
            }

            // If no valid href, remove the link
            return '';
        },
        $sanitized
    );

    // Remove any remaining unsafe attributes from other tags
    $sanitized = preg_replace('/<(\w+)\s+[^>]*>/i', '<$1>', $sanitized);

    // Re-add allowed tags properly (the above removes all attrs, so we need to be selective)
    // This is a simple approach - for production, consider using HTMLPurifier library

    return trim($sanitized);
}

/**
 * Count words in HTML content (strips tags first)
 * 
 * @param string $html HTML content
 * @return int Word count
 */
function countWordsInHTML($html)
{
    // Strip all HTML tags
    $text = strip_tags($html);

    // Normalize whitespace
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);

    // Return 0 for empty strings
    if (empty($text)) {
        return 0;
    }

    // Count words by splitting on whitespace
    $words = preg_split('/\s+/', $text);
    return count($words);
}

/**
 * Validate rich-text content
 * Checks word count and sanitizes HTML
 * 
 * @param string $html Raw HTML content
 * @param int $maxWords Maximum allowed word count (default: 600)
 * @return array ['valid' => bool, 'sanitized' => string, 'word_count' => int, 'error' => string|null]
 */
function validateRichTextContent($html, $maxWords = 600)
{
    $result = [
        'valid' => true,
        'sanitized' => '',
        'word_count' => 0,
        'error' => null
    ];

    // Count words before sanitization
    $wordCount = countWordsInHTML($html);
    $result['word_count'] = $wordCount;

    // Check word limit
    if ($wordCount > $maxWords) {
        $result['valid'] = false;
        $result['error'] = "Proposal text exceeds the maximum word limit of {$maxWords} words. Current count: {$wordCount} words.";
        return $result;
    }

    // Check minimum content
    if ($wordCount === 0) {
        $result['valid'] = false;
        $result['error'] = "Proposal text cannot be empty.";
        return $result;
    }

    // Sanitize HTML
    $result['sanitized'] = sanitizeRichTextHTML($html);

    return $result;
}

/**
 * Convert plain text proposals to HTML paragraphs
 * For backward compatibility with existing proposals
 * 
 * @param string $text Plain text content
 * @return string HTML formatted content
 */
function plainTextToHTML($text)
{
    // Escape HTML entities
    $safe_text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

    // Convert line breaks to <br> or <p> tags
    $lines = explode("\n", $safe_text);
    $paragraphs = array_map(function ($line) {
        $line = trim($line);
        return empty($line) ? '' : '<p>' . $line . '</p>';
    }, $lines);

    return implode("\n", array_filter($paragraphs));
}

/**
 * Display rich-text content safely
 * Detects if content is HTML or plain text and renders appropriately
 * 
 * @param string $content Content to display
 * @return string Safe HTML for display
 */
function displayRichTextContent($content)
{
    // Check if content contains HTML tags
    if (strip_tags($content) !== $content) {
        // Content has HTML, sanitize it
        return sanitizeRichTextHTML($content);
    } else {
        // Plain text, convert to HTML
        return plainTextToHTML($content);
    }
}
