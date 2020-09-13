<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
        $domainId = 1;
        $response = $this->get(route('domains.show', $domainId));
        $response->assertOk();
    }
}
