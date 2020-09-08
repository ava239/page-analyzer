<?php

if (!function_exists('normalizeUrl')) {
    function normalizeUrl(string $url): string
    {
        $parsedUrl = parse_url($url);
        return strtolower("{$parsedUrl['scheme']}://{$parsedUrl['host']}");
    }
}
