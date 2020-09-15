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
        $domainCheckData = [
            'domain_id' => $this->domain->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $domainCheckId = DB::table('domain_checks')->insertGetId($domainCheckData);
        $dom = new Document();
        try {
            $response = Http::get($this->domain->name);
            if ($response->body() !== '') {
                $dom->loadHtml($response->body());
            }
            $checkResult = [
                'check_status' => 'ok',
                'status_code' => $response->status(),
                'h1' => optional($dom->first('h1'))->text(),
                'keywords' => optional($dom->first('meta[name="keywords"]'))->content,
                'description' => optional($dom->first('meta[name="description"]'))->content,
            ];
        } catch (ConnectionException $exception) {
            $checkResult = ['check_status' => 'check_error'];
        }
        DB::table('domain_checks')->where('id', $domainCheckId)->update($checkResult);
    }
}
