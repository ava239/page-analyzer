<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Http;
use Tests\FakeDataSeeder;
use Tests\TestCase;

class DomainCheckTest extends TestCase
{
    use DatabaseMigrations;
    use FakeDataSeeder;

    private const FIXTURES_PATH = __DIR__ . '/../fixtures/';

    public function testStore()
    {
        $lastSeededId = $this->seedFakeData();
        $testHtml = file_get_contents(self::FIXTURES_PATH . 'page.html');

        Http::fake([
            '*' => Http::response($testHtml, 200),
        ]);

        $response = $this->post(route('domains.checks.store', $lastSeededId));

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('domain_checks', [
            'domain_id' => $lastSeededId,
            'h1' => 'test page',
            'keywords' => 'tests, page, analyzer',
            'description' => 'page tester',
        ]);
    }
}
