<?php

namespace App\Jobs;

use App\Models\DomainCheck;
use DiDom\Document;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use function App\Helpers\getStateMachine;

class ProcessDomainCheck implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private object $domainCheck;

    public function __construct($domainCheckId)
    {
        $this->domainCheck = new DomainCheck();
        $this->domainCheck->load($domainCheckId);
    }

    public function handle()
    {
        $stateMachine = getStateMachine($this->domainCheck);
        if ($stateMachine->can('start')) {
            $stateMachine->apply('start');
            try {
                $domain = DB::table('domains')->find($this->domainCheck->data->domain_id);

                $dom = new Document();

                $response = Http::get($domain->name);
                if ($response->body() !== '') {
                    $dom->loadHtml($response->body());
                }
                $checkResult = [
                    'status_code' => $response->status(),
                    'h1' => optional($dom->first('h1'))->text(),
                    'keywords' => optional($dom->first('meta[name="keywords"]'))->content,
                    'description' => optional($dom->first('meta[name="description"]'))->content,
                ];
                $stateMachine->apply('complete', ['result' => $checkResult]);
            } catch (Exception $exception) {
                $stateMachine->apply('error', ['error' => $exception]);
            }
        }
    }
}
