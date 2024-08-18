<?php

namespace App\Helper;

use App\Model\Contact\EmailDTO;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

class EmailSender
{
    private HtmlSanitizerInterface $htmlSanitizer;

    public ?string $emailSubject = null;
    public ?string $emailBody = null;
    public array $additionalHeaders = [];

    public function __construct(HtmlSanitizerInterface $htmlSanitizer)
    {
        $this->htmlSanitizer = $htmlSanitizer;
    }

    public function prepareEmail(EmailDTO $emailDTO): void
    {
        $this->emailSubject = $emailDTO->getSubject();
        $this->emailBody = $emailDTO->getMessage();

        $this->emailBody = $this->htmlSanitizer->sanitize($this->emailBody);
        $this->emailBody = \preg_replace('/((?<!\r)\n|\r(?!\n))/', "\r\n", $this->emailBody);

        $this->additionalHeaders = [
            'From' => \sprintf(
                '%s %s <%s>',
                $emailDTO->getFirstName(),
                $emailDTO->getLastName(),
                $emailDTO->getEmailAddress()
            ),
            'Reply-To' => $emailDTO->getEmailAddress()
        ];
    }

    public function isEmailPrepared(): bool
    {
        if (\is_null($this->emailSubject)) {
            return false;
        }

        if (\is_null($this->emailBody)) {
            return false;
        }

        if (\count($this->additionalHeaders) === 0) {
            return false;
        }

        return true;
    }

    public function sendEmail(string $sendTo): bool
    {
        if (!$this->isEmailPrepared()) {
            return false;
        }

        return \mail(
            to: $sendTo,
            subject: $this->emailSubject,
            message: $this->emailBody,
            additional_headers: $this->additionalHeaders
        );
    }
}
