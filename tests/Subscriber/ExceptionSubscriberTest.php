<?php

namespace App\Tests\Subscriber;

use App\EventSubscriber\ExceptionSubscriber;
use App\Exception\Profile\ProfileNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionSubscriberTest extends KernelTestCase
{
    private ExceptionSubscriber $subscriber;
    private KernelInterface|MockObject $kernelMock;
    private SerializerInterface $serializer;
    private TranslatorInterface $translator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kernelMock = static::getMockBuilder(KernelInterface::class)->getMock();
        $this->subscriber = new ExceptionSubscriber(static::getContainer()->get(TranslatorInterface::class));
        $this->serializer = static::getContainer()->get('serializer');

        $this->translator = static::getContainer()->get('translator');
        $this->translator->setLocale('fr');
    }

    protected function createEvent(\Throwable $expectedException): ExceptionEvent
    {
        return new ExceptionEvent(
            $this->kernelMock,
            new Request(),
            HttpKernelInterface::MAIN_REQUEST,
            $expectedException
        );
    }

    protected function dispatchEvent(ExceptionEvent $event): void
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($this->subscriber);
        $dispatcher->dispatch($event, KernelEvents::EXCEPTION);
    }

    protected function assertResponseContent(ExceptionEvent $event, \Throwable|HttpException $expectedException): void
    {
        static::assertTrue($event->getResponse() instanceof JsonResponse);
        static::assertSame(
            $this->serializer->serialize(
                [
                    'code' => $expectedException instanceof HttpException
                        ? $expectedException->getStatusCode()
                        : $expectedException->getCode(),
                    'message' => $this->translator->trans($expectedException->getMessage()),
                ],
                'json',
                [
                    'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
                ]
            ),
            $event->getResponse()->getContent()
        );
    }

    public function testOnKernelExceptionIfHttpException(): void
    {
        $expectedException = new NotFoundHttpException('Not Found');
        $event = $this->createEvent($expectedException);
        $this->dispatchEvent($event);

        $this->assertResponseContent($event, $expectedException);
    }

    public function testOnKernelExceptionIfBasicException(): void
    {
        $expectedException = new \Exception('My Error');
        $event = $this->createEvent($expectedException);
        $this->dispatchEvent($event);

        $this->assertResponseContent($event, $expectedException);
    }

    public function testOnKernelExceptionWithTranslation(): void
    {
        $expectedException = new ProfileNotFoundException();
        $event = $this->createEvent($expectedException);
        $this->dispatchEvent($event);

        $this->assertResponseContent($event, $expectedException);
    }
}
