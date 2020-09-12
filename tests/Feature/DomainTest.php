<?php

namespace Tests\Feature;

use DB;
use Tests\TestCase;

class DomainTest extends TestCase
{

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
        $response = $this->get(route('domains.show', 1));
        $response->assertOk();
    }
}
