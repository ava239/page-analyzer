<?php

namespace Tests;

use Illuminate\Support\Facades\DB;

trait FakeDataSeeder
{

    public function seedFakeData()
    {
        $fakeDomainsCount = 20;
        for ($i = 0; $i < $fakeDomainsCount; $i++) {
            $lastDomainId = $this->addFakeDomain();
        }
        return $lastDomainId ?? null;
    }

    private function addFakeDomain()
    {
        $url = $this->faker->url;
        return DB::table('domains')->insertGetId([
            'name' => $this->normalizeUrl($url),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function normalizeUrl($url)
    {
        $parsedUrl = parse_url($url);
        return strtolower("{$parsedUrl['scheme']}://{$parsedUrl['host']}");
    }
}
