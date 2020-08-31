<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DomainControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
    }

    public function testStore()
    {
        $data = ['domain' => [
            'name' => 'https://yandex.ru'
        ]];
        $response = $this->post(route('domains.store'), $data);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('domains', $data['domain']);
    }

    public function testShow()
    {
        DB::table('domains')->insertGetId([
            'name' => 'https://yandex.ru',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $response = $this->get(route('domains.show', 1));
        $response->assertOk();
    }
}
