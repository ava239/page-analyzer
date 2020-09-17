<?php

namespace App\Models;

use Finite\StatefulInterface;
use Illuminate\Support\Facades\DB;

class DomainCheck implements StatefulInterface
{
    private string $state;
    public $data;

    public static function create(int $domainId): int
    {
        $domainCheckData = [
            'domain_id' => $domainId,
            'state' => 'new',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        return DB::table('domain_checks')->insertGetId($domainCheckData);
    }

    public function load(int $id)
    {
        $this->data = DB::table('domain_checks')->find($id);
        $this->state = $this->data->state;
    }

    public function getFiniteState(): string
    {
        return $this->state;
    }

    public function setFiniteState($state)
    {
        $this->state = $state;
        DB::table('domain_checks')->where('id', $this->data->id)->update(['state' => $this->state]);
    }

    public function setResult(array $result)
    {
        DB::table('domain_checks')->where('id', $this->data->id)->update($result);
    }
}
