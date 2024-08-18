<?php

declare(strict_types=1);

namespace App\Tests\Controller\Contact;

use App\Exception\Contact\EmailDeliveryException;
use App\Service\Contact\EmailService;
use App\Tests\Commons\ExpectedEmailsTrait;
use App\Tests\Controller\JsonResponseTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ContactControllerTest extends JsonResponseTestCase
{
    use ExpectedEmailsTrait;

    private EmailService|MockObject $emailService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->emailService = $this->createMock(EmailService::class);
        $this->getContainer()->set(EmailService::class, $this->emailService);
    }

    public function testSendEmail(): void
    {
        $this->emailService->expects($this->once())->method('sendEmail')->with($this->newEmail());

        $this->client->jsonRequest(
            method: 'POST',
            uri: '/contact/email',
            parameters: $this->newEmailAsJSONArray(),
        );

        static::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function dataProviderSendEmailInvalidPayload(): array
    {
        return [
            'Send email with invalid payload in en' => [
                'locale' => 'en',
                'firstNameMessage' => 'This value should not be blank.',
                'emailAddressMessage' => 'This value is not a valid email address.',
            ],
            'Send email with invalid payload in fr' => [
                'locale' => 'fr',
                'firstNameMessage' => 'Cette valeur ne doit pas être vide.',
                'emailAddressMessage' => "Cette valeur n'est pas une adresse email valide.",
            ],
        ];
    }

    /**
     * @dataProvider dataProviderSendEmailInvalidPayload
     */
    public function testSendEmailInvalidPayload(
        string $locale,
        string $firstNameMessage,
        string $emailAddressMessage
    ): void {
        $previousException = new ValidationFailedException(null, new ConstraintViolationList([
            new ConstraintViolation($firstNameMessage, null, [], null, 'firstName', null),
            new ConstraintViolation($emailAddressMessage, null, [], null, 'emailAddress', null),
        ]));

        $expectedException = new UnprocessableEntityHttpException('Unprocessable email', $previousException);

        $this->emailService->expects($this->never())->method('sendEmail');

        $newFaultyEmail = $this->newEmailAsJSONArray();
        unset($newFaultyEmail['first_name']);
        $newFaultyEmail['email_address'] = 'test@mail';

        $this->client->jsonRequest(
            method: 'POST',
            uri: '/contact/email',
            parameters: $newFaultyEmail,
            server: ['HTTP_ACCEPT_LANGUAGE' => $locale]
        );

        $this->serializeAndAssertJsonResponseHttpException($expectedException, $locale);
    }

    /**
     * @testWith ["en"]
     *           ["fr"]
     */
    public function testSendEmailFailed(string $locale): void
    {
        $expectedException = new EmailDeliveryException();
        $this->emailService->expects($this->once())->method('sendEmail')->with($this->newEmail())
            ->willThrowException($expectedException);

        $this->client->jsonRequest(
            method: 'POST',
            uri: '/contact/email',
            parameters: $this->newEmailAsJSONArray(),
            server: ['HTTP_ACCEPT_LANGUAGE' => $locale]
        );

        $this->serializeAndAssertJsonResponseHttpException($expectedException, $locale);
    }
}
