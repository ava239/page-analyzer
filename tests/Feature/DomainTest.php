<?php

namespace Tests\Feature;

use Tests\FakeDataSeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DomainTest extends TestCase
{
    use DatabaseMigrations;
    use FakeDataSeeder;

    public function testIndex()
    {
        $this->seedFakeData();
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
        $lastSeededId = $this->seedFakeData();
        $response = $this->get(route('domains.show', $lastSeededId));
        $response->assertOk();
    }
}
