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
        return view('domains.index', ['domains' => $domains]);
    }

    public function store(Request $request)
    {
        $messages = [
            'domain.name.url' => 'Not valid url.',
            'domain.name.required' => 'Domain name should not be empty.'
        ];
        $validator = Validator::make($request->all(), ['domain.name' => 'required|url'], $messages);
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
        return view('domains.show', ['domain' => $domain]);
    }
}
