<?php

namespace App\Tests\Subscriber;

use App\EventSubscriber\ExceptionSubscriber;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

class ExceptionSubscriberTest extends KernelTestCase
{
    public function testOnKernelExceptionIfHttpException(): void
    {
        $subscriber = new ExceptionSubscriber();
        /** @var KernelInterface $kernel */
        $kernel = static::getMockBuilder(KernelInterface::class)->getMock();

        $expectedException = new NotFoundHttpException('Not Found');
        $event = new ExceptionEvent(
            $kernel,
            new Request(),
            HttpKernelInterface::MAIN_REQUEST,
            $expectedException
        );

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event, KernelEvents::EXCEPTION);

        $serializer = static::getContainer()->get('serializer');

        static::assertTrue($event->getResponse() instanceof JsonResponse);
        static::assertSame($serializer->serialize(
            [
                'code' => $expectedException->getStatusCode(),
                'message' => $expectedException->getMessage(),
            ],
            'json',
            [
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
            ]
        ), $event->getResponse()->getContent());
    }
    public function testOnKernelExceptionIfBasicException(): void
    {
        $subscriber = new ExceptionSubscriber();
        /** @var KernelInterface $kernel */
        $kernel = static::getMockBuilder(KernelInterface::class)->getMock();

        $expectedException = new \Exception('My Error');
        $event = new ExceptionEvent(
            $kernel,
            new Request(),
            HttpKernelInterface::MAIN_REQUEST,
            $expectedException
        );

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event, KernelEvents::EXCEPTION);

        $serializer = static::getContainer()->get('serializer');

        static::assertTrue($event->getResponse() instanceof JsonResponse);
        static::assertSame($serializer->serialize(
            [
                'code' => $expectedException->getCode(),
                'message' => $expectedException->getMessage(),
            ],
            'json',
            [
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
            ]
        ), $event->getResponse()->getContent());
    }
}
