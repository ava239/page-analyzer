<?php

namespace Tests\Feature;

use FakeDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $this->seed(FakeDataSeeder::class);
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
        $this->seed(FakeDataSeeder::class);
        $response = $this->get(route('domains.show', 1));
        $response->assertOk();
    }
}
