<?php

namespace App\Helpers;

use App\Models\DomainCheck;
use Finite\Event\TransitionEvent;
use Finite\Loader\ArrayLoader;
use Finite\StateMachine\StateMachine;
use Log;

function normalizeUrl(string $url): string
{
    $parsedUrl = parse_url($url);
    return strtolower("{$parsedUrl['scheme']}://{$parsedUrl['host']}");
}

function getStateMachine(DomainCheck $domainCheck): StateMachine
{
    $stateMachine = new StateMachine();
    $loader = new ArrayLoader([
        'class' => 'DomainCheck',
        'states' => [
            'new' => ['type' => 'initial'],
            'in_progress' => ['type' => 'normal'],
            'error' => ['type' => 'final'],
            'success' => ['type' => 'final'],
        ],
        'transitions' => [
            'start' => ['from' => ['new'], 'to' => 'in_progress'],
            'error' => ['from' => ['in_progress'], 'to' => 'error', 'properties' => ['error' => null]],
            'complete' => ['from' => ['in_progress'], 'to' => 'success', 'properties' => ['result' => []]],
        ],
        'callbacks' => [
            'after' => [
                [
                    'to' => 'success',
                    'do' => function (DomainCheck $object, TransitionEvent $event) {
                        $params = $event->getProperties();
                        $object->setResult($params['result']);
                    }
                ],
                [
                    'to' => 'error',
                    'do' => function ($object, TransitionEvent $event) {
                        $params = $event->getProperties();
                        /** @var \Exception $error */
                        $error = $params['error'];
                        $message = $error->getMessage();
                        Log::channel('domain_checks')->info($message);
                    }
                ]
            ],
        ]
    ]);
    $loader->load($stateMachine);
    $stateMachine->setObject($domainCheck);
    $stateMachine->initialize();
    return $stateMachine;
}
