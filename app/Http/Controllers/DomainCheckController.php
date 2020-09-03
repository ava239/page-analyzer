<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DomainCheckController extends Controller
{
    public function store($id)
    {
        $domain = DB::table('domains')->where('id', $id)->first();
        if (!$domain) {
            abort(404);
        }
        $response = Http::get($domain->name);
        DB::table('domain_checks')->insert([
            'domain_id' => $id,
            'status_code' => $response->status(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        flash('Website has been checked!')->success();
        return redirect()->route('domains.show', $id);
    }
}
