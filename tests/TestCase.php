<?php

namespace Tests;

use DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use WithFaker;
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $domainCount = 20;
        for ($i = 0; $i < $domainCount; $i++) {
            $url = $this->faker->url;
            DB::table('domains')->insert([
                'name' => normalizeUrl($url),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
