<?php

namespace App\Tests\Subscriber;

use App\EventSubscriber\ExceptionSubscriber;
use App\Exception\Profile\ProfileNotFoundException;
use App\Helper\ValidationErrorsParser;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionSubscriberTest extends KernelTestCase
{
    private ExceptionSubscriber $subscriber;
    private KernelInterface|MockObject $kernelMock;
    private SerializerInterface $serializer;
    private TranslatorInterface $translator;
    private ValidationErrorsParser $validationErrorsParser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kernelMock = $this->getMockBuilder(KernelInterface::class)->getMock();
        $this->subscriber = new ExceptionSubscriber(
            $this->getContainer()->get(ValidationErrorsParser::class),
            $this->getContainer()->get(TranslatorInterface::class)
        );
        $this->serializer = $this->getContainer()->get('serializer');

        $this->translator = $this->getContainer()->get('translator');
        $this->translator->setLocale('fr');

        $this->validationErrorsParser = new ValidationErrorsParser();
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
        $validationErrors = $this->validationErrorsParser->getValidationErrors($expectedException);

        static::assertTrue($event->getResponse() instanceof JsonResponse);
        static::assertSame(
            $this->serializer->serialize(
                [
                    'code' => $expectedException instanceof HttpException
                        ? $expectedException->getStatusCode()
                        : $expectedException->getCode(),
                    'message' => $validationErrors ?: $this->translator->trans($expectedException->getMessage()),
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

    public function testOnKernelExceptionIfValidationException(): void
    {
        $expectedException = new ValidationFailedException('', new ConstraintViolationList([
            new ConstraintViolation('Cette valeur ne doit pas être vide.', null, [], null, 'firstName', null),
            new ConstraintViolation("Cette valeur n'est pas une adresse email valide.", null, [], null, 'emailAddress', null),
        ]));

        $event = $this->createEvent($expectedException);
        $this->dispatchEvent($event);

        $this->assertResponseContent($event, $expectedException);

        $expectedException = new UnprocessableEntityHttpException('Unprocessable email', $expectedException);

        $event = $this->createEvent($expectedException);
        $this->dispatchEvent($event);

        $this->assertResponseContent($event, $expectedException);
    }
}
