<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ResponseListener
{
    #[AsEventListener(event: KernelEvents::RESPONSE)]
    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        if (!$response instanceof JWTAuthenticationFailureResponse) {
            return;
        }

        $response->setData(['success'=>false]);
    }
}
