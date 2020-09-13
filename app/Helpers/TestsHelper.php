<?php

if (!function_exists('generateDomainsForTesting')) {
    function generateDomainsForTesting()
    {
        $faker = Faker\Factory::create();
        $domainCount = 20;
        for ($i = 0; $i < $domainCount; $i++) {
            $url = $faker->url;
            DB::table('domains')->insert([
                'name' => normalizeUrl($url),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
