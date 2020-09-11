<?php

namespace App\Jobs;

use DiDom\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ProcessDomainCheck implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private object $domain;

    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    public function handle()
    {
        try {
            $response = Http::get($this->domain->name);
        } catch (ConnectionException $exception) {
            flash("Check error. Could not resolve '{$this->domain->name}'")->error();
            return;
        }
        $dom = new Document();
        if ($response->body() !== '') {
            $dom->loadHtml($response->body());
        }
        $domainCheckData = [
            'domain_id' => $this->domain->id,
            'status_code' => $response->status(),
            'h1' => optional($dom->first('h1'))->text(),
            'keywords' => optional($dom->first('meta[name="keywords"]'))->content,
            'description' => optional($dom->first('meta[name="description"]'))->content,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('domain_checks')->insert($domainCheckData);
        flash("Website '{$this->domain->name}' has been checked!")->success();
    }
}
