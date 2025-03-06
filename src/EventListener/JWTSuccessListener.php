<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class JWTSuccessListener
{
    #[AsEventListener(event: Events::AUTHENTICATION_SUCCESS)]
    public function onJWTAuthSuccess(AuthenticationSuccessEvent $event): void
    {
        if ($event->isPropagationStopped()) {
            return;
        }

        $event->setData(array_merge(['success' => true],$event->getData()));
    }
}
