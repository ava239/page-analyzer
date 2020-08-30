<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DomainController extends Controller
{
    public function index()
    {
        $domains = DB::table('domains')->get();
        return view('domains.index', ['domains' => $domains]);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, ['domain.name' => 'required|url']);
        } catch (ValidationException $e) {
            flash('Not valid url')->error();
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
        return view('domains.show', ['domain' => $domain]);
    }
}
