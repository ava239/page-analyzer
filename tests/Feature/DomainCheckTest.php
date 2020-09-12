<?php

namespace Tests\Feature;

use DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainCheckTest extends TestCase
{

    private const FIXTURES_PATH = __DIR__ . '/../fixtures/';

    public function testStore()
    {
        $url = $this->faker->url;
        $domainId = DB::table('domains')->insertGetId([
            'name' => normalizeUrl($url),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $testHtml = file_get_contents(self::FIXTURES_PATH . 'page.html');
        Http::fake([
            '*' => Http::response($testHtml, 200),
        ]);

        $response = $this->post(route('domains.checks.store', $domainId));

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('domain_checks', [
            'domain_id' => 1,
            'h1' => 'test page',
            'keywords' => 'tests, page, analyzer',
            'description' => 'page tester',
        ]);
    }
}
