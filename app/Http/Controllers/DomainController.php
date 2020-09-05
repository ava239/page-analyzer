<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DomainController extends Controller
{
    public function index()
    {
        $domains = DB::table('domains')
            ->selectSub(
                function ($query) {
                    $query->select('status_code')
                        ->from('domain_checks')
                        ->whereColumn('domain_id', 'domains.id')
                        ->orderByDesc('created_at')
                        ->limit(1);
                },
                'status_code'
            )->selectSub(
                function ($query) {
                    $query->select('created_at')
                        ->from('domain_checks')
                        ->whereColumn('domain_id', 'domains.id')
                        ->orderByDesc('created_at')
                        ->limit(1);
                },
                'last_check'
            )
            ->addSelect('domains.*')
            ->orderByDesc('last_check')
            ->get();
        return view('domains.index', ['domains' => $domains]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['domain.name' => 'required|url']);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                flash($message)->error();
            }
            return back()->withInput();
        }
        $url = parse_url($request->input('domain.name'));
        $normalizedUrl = strtolower("{$url['scheme']}://{$url['host']}");
        $currentId = DB::table('domains')->where('name', $normalizedUrl)->value('id');
        if ($currentId) {
            DB::table('domains')
                ->where('id', $currentId)
                ->update(['updated_at' => now()]);
            flash('Url already exists')->info();
            return redirect()->route('domains.show', $currentId);
        }
        $newId = DB::table('domains')->insertGetId([
            'name' => $normalizedUrl,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        flash('Url has been added')->success();
        return redirect()->route('domains.show', $newId);
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
