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

    private object $domainCheck;

    public function __construct($domainCheckId)
    {
        $this->domainCheck = DB::table('domain_checks')->find($domainCheckId);
    }

    public function handle()
    {
        if ($this->domainCheck->check_status === 'queued') {
            $domain = DB::table('domains')->find($this->domainCheck->domain_id);
            $dom = new Document();
            try {
                $response = Http::get($domain->name);
                if ($response->body() !== '') {
                    $dom->loadHtml($response->body());
                }
                $checkResult = [
                    'check_status' => $response->failed() ? 'request_error' : 'ok',
                    'status_code' => $response->status(),
                    'h1' => optional($dom->first('h1'))->text(),
                    'keywords' => optional($dom->first('meta[name="keywords"]'))->content,
                    'description' => optional($dom->first('meta[name="description"]'))->content,
                ];
            } catch (ConnectionException $exception) {
                $checkResult = ['check_status' => 'connection_error'];
            }
            DB::table('domain_checks')->where('id', $this->domainCheck->id)->update($checkResult);
        }
    }
}
