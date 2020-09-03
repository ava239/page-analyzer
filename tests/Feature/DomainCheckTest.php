<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DomainCheckTest extends TestCase
{
    use DatabaseMigrations;

    public function testStore()
    {
        DB::table('domains')->insertGetId([
            'name' => 'https://yandex.ru',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Http::fake();

        $response = $this->post(route('domains.checks.store', 1));

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('domain_checks', ['domain_id' => 1]);
    }
}
