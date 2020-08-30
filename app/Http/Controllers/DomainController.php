<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $url = parse_url($request->input('domain.name'));
        $url = array_merge(['host' => null, 'scheme' => null], $url);
        if ($url['scheme'] === null || $url['host'] === null) {
            flash('Not valid url')->error();
            return back()->withInput($request->input());
        }
        $normalizedUrl = "{$url['scheme']}://{$url['host']}";
        $currentId = DB::table('domains')->where('name', $normalizedUrl)->value('id');
        if ($currentId) {
            DB::table('domains')
                ->where('id', $currentId)
                ->update(['updated_at' => Carbon::now()]);
            flash('Url already exists')->success();
            return redirect()
                ->route('domains.show', $currentId);
        }
        $newId = DB::table('domains')
            ->insertGetId([
                'name' => $normalizedUrl,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        flash('Url has been added')->success();
        return redirect()
            ->route('domains.show', $newId);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
