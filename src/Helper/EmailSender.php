<?php

namespace App\Helper;

use App\Model\Contact\EmailDTO;
use App\Service\EntitySanitizer;

class EmailSender
{
    private EntitySanitizer $entitySanitizer;

    public ?string $emailSubject = null;
    public ?string $emailBody = null;
    public array $additionalHeaders = [];

    public function __construct(EntitySanitizer $entitySanitizer)
    {
        $this->entitySanitizer = $entitySanitizer;
    }

    public function prepareEmail(EmailDTO $emailDTO): void
    {
        $this->emailSubject = $emailDTO->subject;
        $this->emailBody = $emailDTO->message;

        $this->emailBody = $this->entitySanitizer->sanitizeAndKeepHTMLEntities($this->emailBody);
        $this->emailBody = \preg_replace('/((?<!\r)\n|\r(?!\n))/', "\r\n", $this->emailBody);

        $this->additionalHeaders = [
            'From' => \sprintf(
                '%s %s <%s>',
                $emailDTO->firstName,
                $emailDTO->lastName,
                $emailDTO->emailAddress
            ),
            'Reply-To' => $emailDTO->emailAddress
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
