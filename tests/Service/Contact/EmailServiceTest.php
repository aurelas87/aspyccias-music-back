<?php

namespace App\Tests\Service\Contact;

use App\Exception\Contact\EmailDeliveryException;
use App\Helper\EmailSender;
use App\Service\Contact\EmailService;
use App\Tests\Commons\ExpectedEmailsTrait;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EmailServiceTest extends KernelTestCase
{
    use ExpectedEmailsTrait;

    private EmailSender|MockObject $emailSender;
    private EmailService $emailService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->emailSender = $this->createMock(EmailSender::class);
        $this->emailService = new EmailService($this->emailSender, 'test@mail.com');
    }

    public function testSendEmail(): void
    {
        $newEmail = $this->newEmail();
        $this->emailSender->expects($this->once())->method('prepareEmail')->with($newEmail);
        $this->emailSender->expects($this->once())->method('sendEmail')->with('test@mail.com')->willReturn(true);

        $this->emailService->sendEmail($newEmail);
    }

    public function testSendEmailFailed(): void
    {
        static::expectException(EmailDeliveryException::class);
        static::expectExceptionMessageMatches('/^errors\.contact\.email_delivery$/');

        $newEmail = $this->newEmail();
        $this->emailSender->expects($this->once())->method('prepareEmail')->with($newEmail);
        $this->emailSender->expects($this->once())->method('sendEmail')->with('test@mail.com')->willReturn(false);

        $this->emailService->sendEmail($newEmail);
    }
}
