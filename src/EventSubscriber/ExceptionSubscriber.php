<?php

namespace App\EventSubscriber;

use App\Helper\ValidationErrorsParser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private ValidationErrorsParser $validationErrorsParser;
    private TranslatorInterface $translator;

    public function __construct(ValidationErrorsParser $validationErrorsParser, TranslatorInterface $translator)
    {
        $this->validationErrorsParser = $validationErrorsParser;
        $this->translator = $translator;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        $validationErrors = $this->validationErrorsParser->getValidationErrors($throwable);

        $response = new JsonResponse([
            'code' => $throwable instanceof HttpExceptionInterface
                ? $throwable->getStatusCode()
                : $throwable->getCode(),
            'message' => $validationErrors ?: $this->translator->trans($throwable->getMessage()),
        ]);

        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
