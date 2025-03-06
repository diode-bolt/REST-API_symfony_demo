<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Contracts\EventDispatcher\Event;

final class ApiExceptionListener
{
    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$this->checkEvent($event, $event->getRequest())) {
            return;
        }

        $exception = $event->getThrowable();

        if (!($exception->getPrevious() instanceof ValidationFailedException)) {
            return;
        }

        $violations = $exception->getPrevious()->getViolations();
        $errors = $this->formatValidationErrors($violations);

        $event->setResponse(new JsonResponse(
            ['success' => false, 'message' => 'Validation failed','errors' => $errors],
            Response::HTTP_UNPROCESSABLE_ENTITY
        ));
    }

    private function isApiRequest(Request $request): bool
    {
        return str_starts_with($request->getPathInfo(), '/api/')
            || $request->getPreferredFormat() === 'json'
            || $request->getContentTypeFormat() === 'json';
    }

    private function formatValidationErrors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }

    private function checkEvent(Event $event, Request $request): bool
    {
        if ($event->isPropagationStopped()) {
            return false;
        }

        if (!$this->isApiRequest($request)) {
            return false;
        }

        return true;
    }
}
