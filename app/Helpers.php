<?php

namespace App\Helpers;

function normalizeUrl(string $url): string
{
    $parsedUrl = parse_url($url);
    return strtolower("{$parsedUrl['scheme']}://{$parsedUrl['host']}");
}
