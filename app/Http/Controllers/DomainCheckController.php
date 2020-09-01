<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DomainCheckController extends Controller
{
    public function store($id)
    {
        $domain = DB::table('domains')->where('id', $id)->first();
        if (!$domain) {
            abort(404);
        }
        DB::table('domain_checks')->insert([
            'domain_id' => $id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        flash('Website has been checked!')->success();
        return redirect()->route('domains.show', $id);
    }
}
