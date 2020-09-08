<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{
    public function index()
    {
        $domains = DB::table('domains')->get();
        $checks = DB::table('domain_checks')
            ->select(['created_at','status_code','domain_id'])
            ->distinct('domain_id')
            ->orderBy('domain_id')
            ->orderByDesc('created_at')
            ->get();

        $domainChecks = $domains->map(function ($domain) use ($checks) {
            $currentDomainChecks = $checks->where('domain_id', $domain->id);
            $emptyCheck = [
                'created_at' => '-',
                'status_code' => '-',
                'domain_id' => $domain->id
            ];

            return $currentDomainChecks->whenEmpty(
                fn() => $emptyCheck,
                fn($checksList) => (array) $checksList->first()
            );
        });

        return view('domains.index', [
            'domains' => $domains,
            'domainChecks' => $domainChecks->keyBy('domain_id'),
        ]);
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
