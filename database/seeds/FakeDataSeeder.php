<?php

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;

class FakeDataSeeder extends Seeder
{
    use WithFaker;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setUpFaker();
        $this->seedFakeData();
    }

    private function seedFakeData($domainCount = 20)
    {
        for ($i = 0; $i < $domainCount; $i++) {
            $this->addFakeDomain();
        }
    }

    private function addFakeDomain()
    {
        $url = $this->faker->url;
        DB::table('domains')->insert([
            'name' => normalizeUrl($url),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
