<?php

namespace App\Helpers;

use App\Models\DomainCheck;

function normalizeUrl(string $url): string
{
    $parsedUrl = parse_url($url);
    return strtolower("{$parsedUrl['scheme']}://{$parsedUrl['host']}");
}
