<?php

namespace Tests\Feature;

use DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

use function Tests\Helpers\generateDomainsForTesting;

class DomainCheckTest extends TestCase
{

    private const FIXTURES_PATH = __DIR__ . '/../fixtures/';

    public function setUp(): void
    {
        parent::setUp();
        generateDomainsForTesting();
    }

    public function testStore()
    {
        $testHtml = file_get_contents(self::FIXTURES_PATH . 'page.html');
        Http::fake([
            '*' => Http::response($testHtml, 200),
        ]);

        $domain = DB::table('domains')->inRandomOrder()->first();
        $response = $this->post(route('domains.checks.store', $domain->id));

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('domain_checks', [
            'domain_id' => $domain->id,
            'h1' => 'test page',
            'keywords' => 'tests, page, analyzer',
            'description' => 'page tester',
        ]);
    }
}
