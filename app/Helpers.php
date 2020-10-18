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

function makeStateMachine(DomainCheck $domainCheck): StateMachine
{
    $stateMachine = new StateMachine();

    $stateMachine->addState(new State('new', StateInterface::TYPE_INITIAL));
    $stateMachine->addState('started');
    $stateMachine->addState(new State('failed', StateInterface::TYPE_FINAL));
    $stateMachine->addState(new State('performed', StateInterface::TYPE_FINAL));

    $start = new Transition('start', 'new', 'started');
    $stateMachine->addTransition($start);

    $getOptionsResolver = function ($options) {
        $resolver = new OptionsResolver();
        $resolver->setDefaults($options);
        return $resolver;
    };

    $fail = new Transition('fail', 'started', 'failed', null, $getOptionsResolver(['error' => null]));
    $stateMachine->addTransition($fail);

    $perform = new Transition('finish', 'started', 'performed', null, $getOptionsResolver(['result' => []]));
    $stateMachine->addTransition($perform);

    $stateMachine->getDispatcher()->addListener(
        'finite.post_transition.finish',
        function (TransitionEvent $event) use ($stateMachine) {
            $params = $event->getProperties();
            $stateMachine->getObject()->setResult($params['result']);
        }
    );
    $stateMachine->getDispatcher()->addListener(
        'finite.post_transition.fail',
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
