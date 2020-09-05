<?php

namespace App\Http\Controllers;

use DiDom\Document;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DomainCheckController extends Controller
{
    public function store($id)
    {
        $domain = DB::table('domains')->where('id', $id)->first();
        if (!$domain) {
            abort(404);
        }
        $response = Http::get($domain->name);
        $dom = new Document();
        if ($response->body()) {
            $dom->loadHtml($response->body());
        }
        $domainCheckData = [
            'domain_id' => $id,
            'status_code' => $response->status(),
            'h1' => optional($dom->first('h1'))->text(),
            'keywords' => optional($dom->first('meta[name="keywords"]'))->content,
            'description' => optional($dom->first('meta[name="description"]'))->content,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('domain_checks')->insert($domainCheckData);
        flash('Website has been checked!')->success();
        return redirect()->route('domains.show', $id);
    }
}
