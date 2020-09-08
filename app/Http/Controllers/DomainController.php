<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{
    public function index()
    {
        $domains = DB::table('domains')
            ->select(['domains.*', 'domain_checks.created_at as last_check', 'domain_checks.status_code'])
            ->leftJoin('domain_checks', 'domains.id', '=', 'domain_checks.domain_id')
            ->leftJoin('domain_checks as dc_limit', function (JoinClause $join) {
                $join
                    ->on('domains.id', '=', 'dc_limit.domain_id')
                    ->on('dc_limit.created_at', '>', 'domain_checks.created_at');
            })
            ->whereNull('dc_limit.id')
            ->orderByDesc('last_check')
            ->get();
        return view('domains.index', ['domains' => $domains]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['domain.name' => 'required|url']);
        if ($validator->fails()) {
            $errors = collect($validator->errors()->all());
            $errors->map(fn($message) => flash($message)->error());
            return back()->withInput();
        }

        $normalizedUrl = normalizeUrl($request->input('domain.name'));
        $domain = DB::table('domains')->where('name', $normalizedUrl)->first();

        if ($domain) {
            flash('Url already exists')->info();
            return redirect()->route('domains.show', $domain->id);
        }

        $id = DB::table('domains')->insertGetId([
            'name' => $normalizedUrl,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        flash('Url has been added')->success();
        return redirect()->route('domains.show', $id);
    }

    public function show($id)
    {
        $domain = DB::table('domains')->where('id', $id)->first();
        if (!$domain) {
            abort(404);
        }
        $domainChecks = DB::table('domain_checks')
            ->where('domain_id', $id)
            ->orderByDesc('created_at')
            ->get();
        return view('domains.show', ['domain' => $domain, 'checks' => $domainChecks]);
    }
}
