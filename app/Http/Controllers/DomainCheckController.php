<?php

namespace App\Http\Controllers;

use DiDom\Document;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DomainCheckController extends Controller
{
    public function store($domainId)
    {
        $domain = DB::table('domains')->find($domainId);
        abort_unless($domain !== null, 404);

        try {
            $response = Http::get($domain->name);
        } catch (ConnectionException $exception) {
            flash(__('check_error'))->error();
            return back();
        }

        $dom = new Document();
        if ($response->body() !== '') {
            $dom->loadHtml($response->body());
        }

        $checkResult = [
            'domain_id' => $domainId,
            'created_at' => now(),
            'updated_at' => now(),
            'status_code' => $response->status(),
            'h1' => optional($dom->first('h1'))->text(),
            'keywords' => optional($dom->first('meta[name="keywords"]'))->content,
            'description' => optional($dom->first('meta[name="description"]'))->content,
        ];
        DB::table('domain_checks')->insert($checkResult);

        flash(__('site_checked'))->info();

        return redirect()->route('domains.show', $domainId);
    }
}
