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
        $domainCheckData = [
            'domain_id' => $id,
            'check_status' => 'queued',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $domainCheckId = DB::table('domain_checks')->insertGetId($domainCheckData);
        flash('Domain check scheduled')->info();
        ProcessDomainCheck::dispatch($domainCheckId);
        return redirect()->route('domains.show', $id);
    }
}
