<?php

declare(strict_types=1);

//global helper function for base url
if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $baseUrl = 'http://localhost:8080';
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}
