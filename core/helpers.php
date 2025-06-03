<?php
/**
 * Helpers File
 * Generates full urls and helps escaping html characters to prevent XSS
 * 
 */
declare(strict_types=1);

/**
 * Generate a full URL to a given path. 
 * 
 * @param null|string $path  The given path to a specific view
 */
function url(?string $path = ''): string
{
    return rtrim(BASE_URL_PATH, '/') . '/' . ltrim($path, '/');
}

/**
 * Escape output for safe HTML rendering (prevents XSS).
 * 
 * @param string $value  The given output value to be escaped
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}


/**
 * Generate the full URL to an asset name
 * 
 * @param string $path  The given path to an asset like JS or CSS
 */
function asset(string $path): string
{
    return url('/') . ltrim($path, '/');
}


