<?php

namespace Tests\Feature;

use FakeDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainCheckTest extends TestCase
{
    use RefreshDatabase;

    private const FIXTURES_PATH = __DIR__ . '/../fixtures/';

    public function testStore()
    {
        $this->seed(FakeDataSeeder::class);
        $testHtml = file_get_contents(self::FIXTURES_PATH . 'page.html');
        Http::fake([
            '*' => Http::response($testHtml, 200),
        ]);

        $response = $this->post(route('domains.checks.store', 1));

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
