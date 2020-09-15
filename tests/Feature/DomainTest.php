<?php

namespace Tests\Feature;

use DB;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function App\Helpers\normalizeUrl;
use function Tests\Helpers\generateDomainsForTesting;

class DomainTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        generateDomainsForTesting();
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

        $this->assertDatabaseHas('domains', ['name' => normalizeUrl($url)]);
    }

    public function testShow()
    {
        $domain = DB::table('domains')->inRandomOrder()->first();
        $response = $this->get(route('domains.show', $domain->id));
        $response->assertOk();
    }
}
