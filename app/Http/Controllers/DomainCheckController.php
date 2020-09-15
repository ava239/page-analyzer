<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessDomainCheck;
use Illuminate\Support\Facades\DB;

class DomainCheckController extends Controller
{
    public function store($id)
    {
        $domain = DB::table('domains')->find($id);
        abort_unless($domain !== null, 404);
        ProcessDomainCheck::dispatch($domain);
        flash('Domain check scheduled')->info();
        return redirect()->route('domains.show', $id);
    }
}
