<?php

namespace App\Tests\Helper;

use App\Helper\EmailSender;
use App\Tests\Commons\ExpectedEmailsTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

class EmailSenderTest extends KernelTestCase
{
    use ExpectedEmailsTrait;

    private EmailSender $emailSender;

    protected function setUp(): void
    {
        parent::setUp();

        $this->emailSender = new EmailSender($this->getContainer()->get(HtmlSanitizerInterface::class));
    }

    public function testPrepareEmail(): void
    {
        static::assertSame($this->emailSender->emailSubject, null);
        static::assertSame($this->emailSender->emailBody, null);
        static::assertSame($this->emailSender->additionalHeaders, []);
        static::assertFalse($this->emailSender->isEmailPrepared());

        $email = $this->newEmail();
        $this->emailSender->prepareEmail($email);

        static::assertSame($email->getSubject(), $this->emailSender->emailSubject);
        static::assertSame(self::SANITIZED_EMAIL_BODY, $this->emailSender->emailBody);

        $expectedAdditionalHeaders = $this->getExpectedHeaders($email);
        static::assertCount(\count($expectedAdditionalHeaders), $this->emailSender->additionalHeaders);
        foreach ($expectedAdditionalHeaders as $headerName => $headerContent) {
            static::assertArrayHasKey($headerName, $this->emailSender->additionalHeaders);
            static::assertSame($headerContent, $this->emailSender->additionalHeaders[$headerName]);
        }

        static::assertTrue($this->emailSender->isEmailPrepared());
    }

    public function testSendEmailIfNull(): void
    {
        $result = $this->emailSender->sendEmail('test@mail.com');
        static::assertFalse($result);
    }
}
