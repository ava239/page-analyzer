<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessDomainCheck;
use App\Models\DomainCheck;
use Illuminate\Support\Facades\DB;

class DomainCheckController extends Controller
{
    public function store($domainId)
    {
        $domain = DB::table('domains')->find($domainId);
        abort_unless($domain !== null, 404);

        $domainCheckId = DomainCheck::create($domainId);
        ProcessDomainCheck::dispatch($domainCheckId);
        flash(__('check_scheduled'))->info();

        return redirect()->route('domains.show', $domainId);
    }
}
