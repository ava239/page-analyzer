<?php

namespace App\Helpers;

use App\Models\DomainCheck;
use Finite\Event\TransitionEvent;
use Finite\State\State;
use Finite\State\StateInterface;
use Finite\StateMachine\StateMachine;
use Finite\Transition\Transition;
use Log;
use Symfony\Component\OptionsResolver\OptionsResolver;

function normalizeUrl(string $url): string
{
    $parsedUrl = parse_url($url);
    return strtolower("{$parsedUrl['scheme']}://{$parsedUrl['host']}");
}

function getStateMachine(DomainCheck $domainCheck): StateMachine
{
    $stateMachine = new StateMachine();

    $stateMachine->addState(new State('new', StateInterface::TYPE_INITIAL));
    $stateMachine->addState('in_progress');
    $stateMachine->addState(new State('error', StateInterface::TYPE_FINAL));
    $stateMachine->addState(new State('success', StateInterface::TYPE_FINAL));

    $startTransition = new Transition('perform_check', 'new', 'in_progress');
    $stateMachine->addTransition($startTransition);

    $getOptionsResolver = function ($options) {
        $resolver = new OptionsResolver();
        $resolver->setDefaults($options);
        return $resolver;
    };

    $error = new Transition('error', 'in_progress', 'error', null, $getOptionsResolver(['error' => null]));
    $stateMachine->addTransition($error);

    $complete = new Transition('complete', 'in_progress', 'success', null, $getOptionsResolver(['result' => []]));
    $stateMachine->addTransition($complete);

    $stateMachine->getDispatcher()->addListener(
        'finite.post_transition.complete',
        function (TransitionEvent $event) use ($stateMachine) {
            $params = $event->getProperties();
            $stateMachine->getObject()->setResult($params['result']);
        }
    );
    $stateMachine->getDispatcher()->addListener(
        'finite.post_transition.error',
        function (TransitionEvent $event) {
            $params = $event->getProperties();
            /** @var \Exception $error */
            $error = $params['error'];
            $message = $error->getMessage();
            Log::channel('domain_checks')->info($message);
        }
    );

    $stateMachine->setObject($domainCheck);
    $stateMachine->initialize();

    return $stateMachine;
}
