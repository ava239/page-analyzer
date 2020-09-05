<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DomainTest extends TestCase
{
    use DatabaseMigrations;

    private $lastDomainId;

    public function setUp(): void
    {
        parent::setUp();
        for ($i = 0; $i < 20; $i++) {
            $this->lastDomainId = $this->addFakeDomain();
        }
    }

    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
    }

    public function testStore()
    {
        $url = $this->faker->url;
        $data = ['domain' => [
            'name' => $url
        ]];
        $response = $this->post(route('domains.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('domains', ['name' => $this->normalizeUrl($url)]);
    }

    public function testShow()
    {
        $response = $this->get(route('domains.show', $this->lastDomainId));
        $response->assertOk();
    }

    private function normalizeUrl($url)
    {
        $parsedUrl = parse_url($url);
        return strtolower("{$parsedUrl['scheme']}://{$parsedUrl['host']}");
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
}
